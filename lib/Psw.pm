package Dard::Psw;

use 5.30.0;
use strict;
use warnings;

#our $VERSION = '0.00.01';

use lib '/home/' . getpwuid($<) . '/.local/share/perl/';

use IO::Socket::INET;
use Dard::FCGI qw / $FCGI fcgi_response /;
use Data::Dump qw/ dd /;

our $VERSION = '0.00.01';

my $server;                 # The socket is bound to this variable
my $queue         = {};     # Queue ( internal ) for the request sent from server
my $_sock_memmory = 512;    # Memmory in megabytes alocated

#my $SOCK_PATH = '/var/run/psw.sock' or /run/psw.sock';
my $SOCK_PATH = '/home/' . getpwuid($<) . '/.local/run/psw.sock';

sub new
{
    my ( $psw, @args ) = @_;
    bless {}, $psw;
}

sub options
{
    my ($psw) = shift;
    if ( @ARGV >= 1 ) {

        for ( $ARGV[0] ) {

            ( /--help/ || /-h/ ) && do { &_small_help; last };
            /--start/            && do { $psw->start;  last };
            /--stop/             && do { $psw->stop;   last };

            die 'Argument not implemented';
        }
    }
}

sub stop
{
    my ($psw) = shift;
    if ( defined($server) ) {

        #server->shutdown(SHUT_RD);       # we stopped reading data
        #server->shutdown(SHUT_WR);       # we stopped writing data
        $server->shutdown(SHUT_RDWR);    # we stopped using this socket

        $server->close();

        unlink $SOCK_PATH if ( -e $SOCK_PATH );

    }

} ## end sub stop

sub start
{
    my ($psw) = shift;

    # unlink $SOCK_PATH
    if ( -e $SOCK_PATH ) {
        unlink $SOCK_PATH or die 'Could not delete the socket file : ' . $SOCK_PATH;
    }

    $server = IO::Socket::UNIX->new(
        Type   => SOCK_STREAM,
        Local  => $SOCK_PATH,
        Listen => 25,

    ) or die "Can't create socket - " . $! || $IO::Socket::errstr;

    # Make sure the file has read write permisions from the programs you wish
    # The bellow set is a very wide open for every program
    chmod 0666, $SOCK_PATH;

    $server->autoflush(1);

    while (1) {

        my $client;

        # my $data;

        $client = $server->accept();

        # Unix client
        #my $client_path = $client->peerpath();
        #my $host_path = $client->hostpath();

        # $client->recv( $data, $_sock_memmory * 1024 * 1024, 0 );

        _data_loop($client);

    } ## end while (1)

} ## end sub start

# A handy tool for debuging in the console

sub _spl
{
    my $ref = shift;
    my @arr = $ref =~ /./sg;    #split( '', $ref);
    my $val;
    my $str;
    foreach ( $$ref =~ /./g ) {
        $str .= $_;
        $val = ord($_);
        $val < 32 ? print "\n" . $val . '    ==== ' . "\n" : print $_ if ( $val <= 127 );
        print "\n" . $_                                               if ( $val > 127 );

        #say "\n" . $val . ' +===+ ' . $_                     if ( $val > 127 );
    }

    print 'The size of buff is: ' . length($str) / 1024 . 'kb' . "\n" if ($str);
    1;
} ## end sub _spl

sub _get_params
{
    my ( $params, $id ) = @_;

    my $pos = 0;
    my $len = length($$params);
    my $k_l = 0;
    my $v_l = 0;
    my $key = '';
    my $val = '';

    while ( $pos < $len ) {

        $k_l = _params_length( $params, \$pos );
        $v_l = _params_length( $params, \$pos );

        $key = substr( $$params, $pos, $k_l );
        $pos += $k_l;

        $val = substr( $$params, $pos, $v_l );
        $pos += $v_l;

        $queue->{$id}{params}{$key} = $val;

        # Uncomment the lines bellow for debuging
        #say $k_l . '===== ' . $v_l;
        #say $key . " ::: " . $val;

    } ## end while ( $pos < $len )

} ## end sub _get_params

sub _char_2_num_char
{
    my ( $str, $pos ) = @_;
    my $cr = ord( substr( $$str, $$pos, 1 ) );

    $cr <= 127 ? $cr : undef;
}

sub _char_2_num_int
{
    my ( $str, $pos ) = @_;
    my $n = substr( $$str, $pos, 4 );

    # Encoding decoding short example
    #( ( $aa & 0x7f ) << 24 ) + ( $bb << 16 ) + ( $cc << 8 ) + $dd;

    # Decoding
    ( ( ( 0+ ord( substr( $n, 0, 1 ) ) & hex "0x7F" ) ) << 24 ) + ( 0+ ord( substr( $n, 1, 1 ) ) << 16 ) + ( 0+ ord( substr( $n, 2, 1 ) ) << 8 )
        + ord( substr( $n, 3, 1 ) );
}

sub _params_length
{
    my ( $str, $pos ) = @_;
    my $num = _char_2_num_char( $str, $pos );

    if ( defined $num ) {
        $$pos += 1;

        return $num;
    } else {
        $$pos += 4;
        _char_2_num_int( $str, $$pos - 4 );
    }
}

sub _get_begin_content
{
    my ( $content, $id, $header ) = @_;
    my $str = '';

    if ( $header->{content_length} > 0 ) {
        foreach ( $$content =~ /./g ) {
            $str .= ord($_) . ',';
        }
        $queue->{$id}{begin_body} = substr( $str, 0, -1 );
    }
}

sub _get_stdin_content
{
    my ( $content, $id, $header ) = @_;
    push( @{ $queue->{$id}{stdin} }, $$content ) if ( $header->{content_length} > 0 );

    # The line bellow made the program to run out of memmory :))
    # $queue->{$id}{stdin}[ ( 0+ $queue->{$id}{stdin} ) ] = $content if ( $header->{content_length} > 0 );
}

sub _data_loop
{
    my ($client) = @_;
    my $stop     = 0;    # Semaphore flag for the inner loop passed down to _data_parse sub
    my $long     = 0;    # Semaphore flag, a local one in the inner loop
    my $data;
    my $req_id;

    $client->recv( $data, $_sock_memmory * 1024 * 1024, 0 );

    $req_id = _data_parser( \$data, $client, \$stop );

    if ($data) {

        if ( $stop == 1 ) {

            # say "========================================\n========================================\n\n";
            undef $data;
            return;
        }
        while ( $stop == 0 ) {

            # Don't want to parse twice the first stream batch from a long stream
            if ( $long > 0 ) {

                my $buff = $data;

                if ( $buff && length($buff) > 0 ) {

                    $req_id = _data_parser( \$buff, $client, \$stop );

                    $data = '';
                    $buff = '';

                    # Uncomment the say statements below for debuging
                    if ( $stop == 1 ) {

                        # say "========================================\n========================================\n\n";
                        $data = '' && undef $data;
                        $buff = '' && undef $buff;
                        return;
                    } else {

                        # say "\n===========================================\n\n";
                    }
                } ## end if ( $buff && length($buff...))

            } ## end if ( $long > 0 )

            $data = '';

            $client->recv( $data, $_sock_memmory * 1024 * 1024, 0 );

            $long = 1;

        } ## end while ( $stop == 0 )

    } ## end if ($data)

} ## end sub _data_loop

sub _data_parser
{
    my ( $data, $client, $stop ) = @_;
    my $req_id;
    if ( defined $data && $$data ne '' ) {

        my ( $header, $pos, $content, $len );
        $len = length($$data);
        $pos = 0;

        while ( $pos <= $len ) {
            undef $header;

            #if ( $header = _header_get( $data, $pos ) ) {
            $header = _header_get( $data, $pos, $len );
            if ( defined $header ) {

                if ( $header->{padding_length} ) {
                    $content = substr( $header->{content}, 0, -$header->{padding_length} );
                } else {
                    $content = substr( $header->{content}, 0 );
                }
                delete $header->{content} && undef $header->{$content};

                $pos += ( 8 + $header->{content_length} + $header->{padding_length} );

                _queue_add( $header->{type}, $header->{request_id}, \$content, $header, $client, $stop );

                $req_id = $header->{request_id};

                # Uncoment bellow for debuging
                # say 'Total pos  : ' . $pos . ' : $len : ' . $len;
                # dd $header;

                if ( $pos >= $len ) {
                    $pos = 0;

                    $content = '' && undef $content;
                    $header  = '' && undef $header;

                    return $req_id;
                }

            } else {
                warn "Don't know what happened but we are out of sub : _data_parse.";

                $pos   = 0;
                $$stop = 1;

                return;

            }
        } ## end while ( $pos <= $len )
    } ## end if ( defined $data && ...)

} ## end sub _data_parser

sub _queue_add
{
    my ( $type, $id, $content, $header, $client, $stop ) = @_;

    my $flag = 0;    # Semaphore flag. Set to 1 when request is recevied whole

    if ( !defined $type ) {
        die 'WE HAVE NO TYPE TO COMPARE TO iIN _que_add.';
    }

    # BEGIN header 1
    # $fcgi->FCGI('BEGIN_REQUEST')
    if ( $type == $FCGI->{BEGIN_REQUEST} ) {
        _queue_init($id);
        $queue->{$id}{fcgi_type} = $type;
        _get_begin_content( $content, $id, $header );
    }

    # ABORT request 2
    # $psw->FCGI('ABORT_REQUEST');
    elsif ( $type == $FCGI->{ABORT_REQUEST} ) {
        warn 'Future Not implemented yet : ABORT METHOD';
    }

    # PARAMS procesing 4
    # $fcgi->FCGI('PARAMS')
    elsif ( $type == $FCGI->{PARAMS} ) {

        _get_params( $content, $id );
    }

    # STDIN procesing 5
    # $fcgi->FCGI('STDIN)
    elsif ( $type == $FCGI->{STDIN} ) {

        _get_stdin_content( $content, $id, $header );

        if ( $header->{content_length} == 0 ) {
            $flag = 1;
            $queue->{$id}{status} = 'complete';
        }

    }

    # DATA processing 8
    # $fcgi->FCGI('DATA')
    elsif ( $type == $FCGI->{DATA} ) {
        warn 'Future Not implemented yet : DATA METHOD';
    }

    #GET_VALUES proccesing 9
    # $fcgi->FCGI('GET_VALUES')
    elsif ( $type == $FCGI->{GET_VALUES} ) {
        warn 'Future Not implemented yet : GET_VALUES METHOD';
    }

    # END_REQUEST type 3
    # $fcgi->FCGI('END_REQUEST')
    elsif ( $type == $FCGI->{END_REQUEST} ) {
        $flag = 1;
    }

    # Here should go the exception handling
    else {
        $$stop = 1;
        warn "Exception handling not yet implemented . Could not handle the uncought type of : " . $type;
    }

    # Here we are done with the request receving, handle the data and print back
    if ( $flag == 1 ) {
        _print( $id, $client );

        $$stop = 1;    # Semaphore flag for the _data_loop inner loop

        # dd $queue;

        delete $queue->{$id} && undef $queue->{$id};    # We are done here clean up the $queue

    }
} ## end sub _queue_add

sub _header_get
{
    my ( $data, $pos, $len ) = @_;
    my ( $header, $current );

    if ( $pos <= $len - 8 ) {
        $current = substr( $$data, $pos, 8 );
    } else {
        $current = '';
    }
    if ( length($current) == 8 ) {

        $header = {
            version        => unpack( 'C',   $current ),
            type           => unpack( 'xC',  $current ),
            request_id     => unpack( 'xxn', $current ),
            content_length => unpack( 'x4n', $current ),
            padding_length => unpack( 'x6C', $current ),
            reserved       => unpack( 'x7C', $current ),
            content        => ''
        };

        if ( defined $header->{content_length} ) {
            if ( ( $header->{content_length} + $header->{padding_length} ) > 0 ) {
                $header->{content} = substr( $$data, $pos + 8, $header->{content_length} + $header->{padding_length} );
            }
        }

        return $header;

    } else {

        return;

    }
} ## end sub _header_get

sub _queue_init
{
    my $id = shift;

    if ( !defined $queue->{$id} ) {
        $queue->{$id}              = {};
        $queue->{$id}{fcgi_type}   = 1;
        $queue->{$id}{begin_body}  = '';
        $queue->{$id}{params}      = {};
        $queue->{$id}{stdin}       = [];
        $queue->{$id}{data}        = [];
        $queue->{$id}{post}        = {};
        $queue->{$id}{post_method} = '';
        $queue->{$id}{ip}          = '';
        $queue->{$id}{user_agent}  = '';
        $queue->{$id}{files}       = {};
        $queue->{$id}{url}         = '';
        $queue->{$id}{status}      = 'loading_stdin';
    } ## end if ( !defined $queue->...)

} ## end sub _queue_init

sub _print
{
    my ( $id, $client ) = @_;

    my $refh = { request_id => $id, content => _pack_test_psw($id) };

    my $response = fcgi_response( { request_id => $id, content => _pack_test_psw($id) } );    #

    my $send = $client->send($response);                                                      # Return the size of text sent

}

sub _testheader
{
    my $txt    = 'Some text';
    my $req_id = 2;
    pack( 'CCnnCC', 1, 5, $req_id, length($txt), 0, 0 ) . $txt;
}

sub _small_help
{
    my $help = <<~'HERE';
	psw [--help --start --stop] [-h]
	HERE
    print $help;
}

# Create a dummy small page with a form in it
sub _test_form_page
{
    my ( $doc, $text ) = @_;
    my $here = <<~"EOF";
		<!DOCTYPE html>
		<html lang = "en">
			<head>
				<meta charset ="utf-8">
				<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=5">
				<title> Dard::psw - Perl FCGI </title>
				<link rel = "stylesheet" href ="style.css">
				<script src="script.js"> </script>
				<style>
					html, body {font-size: 16px}
					form { border: 1px solid #bcbcbc; border-radius: 6px; padding: 20px; width: auto;}
					h1 {text-align: center; font-size: 25px; display:block; margin: 35px auto; width: 40%; min-width:320px;}
					.row-wrap {width:55%; min-width:320px; margin: 0 auto 60px;}
					.row {width:90%; min-width: 320px; margin: 0 auto;clear:both;padding:4px;  border-bottom: 1px solid #ecd6d6; display: table;min-width: 780px; }
					:nth-child(2n+1  of .row) {background-color: #f2f2f2 }
					.left-column {width:35%;float:left; word-break: break-all; text-indent: 10px;}
					.middle-column { float:left;width:5%;text-align:center; opacity: .5;}
					.rigth-column { width:60%;float:left;word-break: break-all; }
					.centerdiv { width: 60%; min-width: 320px; margin: 50px auto; max-width: 600px;}
					.cfield {width: 80%; min-width: 260px; margin: 20px auto; display: block; height: 35px; line-height: 33px; border-radius: 6px; border: 1px solid #acacac;box-sizing: border-box}
					.cfield:focus, .cfield:focus-visible { border: 1px solid #8c8686; outline:none;}
					.ltext { text-align: left; text-indent: 10px;}
					.ctext { text-align: center;}
					.bton { text-align: center; display: block; width: 120px; margin: 20px auto; border: 1px solid #2e0b4e; background: ##f1e7f7; border-radius:6px; height: 35px;}
					\@media only screen and (max-width: 660px) { 
						.row{ width: 96%;min-width: 280px; }
						.left-column { width: 90%}
						.middle-column { width: 10%;opacity: .2;}
						.rigth-column { width: 100%;margin-top: 8px; border-top: 1px solid #dbdee1;}
					}
				</style>
			</head>
			<body>
				<h1>Dard::psw test page</h1>
				<!--page content -->
				<div class ="centerdiv">
				    <div style = "margin-bottom: 20px;">$text</div>
				    <form method="post" enctype="multipart/form-data">
				    	<input type ="text" name ="foo" class="ltext cfield" placeholder ="foo">
					    <input type ="text" name ="bar" class="ltext cfield" placeholder ="bar">
					    <input type ="file" name ="mfile" class="ltext cfield">
					    <input type ="reset" value ="Reset" class="bton">
					    <input type="submit" value="send" class="bton">
				    </form>
				</div>
				$doc
			</body>
		</html>
		EOF

    # enctype="multipart/form-data"
    # <input type ="file" name ="mfile">
} ## end sub _test_form_page

# wrap %ENV hash in HTML
sub _wrap_http_env
{
    my $id = shift;
    my ( $val, $doc );

    $doc = "\n\t\t" . '<br>' . "\n\tt" . '<div class="row-wrap">' . "\n";

    if ($id) {
        foreach my $var ( sort( keys( %{ $queue->{$id}{params} } ) ) ) {
            $val = $queue->{$id}{params}{$var};

            #$val =~ s|\n|\\n|g;
            #$val =~ s|"|\\"|g;
            $doc
                .= "\t\t\t<div class=\"row\">\n\t\t\t\t<div class=\"left-column\">${var}</div>\n\t\t\t\t<div class=\"middle-column\"> = </div>\n\t\t\t\t<div class=\"rigth-column\">\"${val}\"</div>\n\t\t\t</div>\n";
        }
    } else {
        say '';
    }
    return $doc .= "\t\t" . '</div>' . "\n";
} ## end sub _wrap_http_env

sub _pack_test_psw
{
    my $id     = shift;
    my $header = "Content-Type: text/html\n\n";
    my $txt    = 'Hello my friend';
    my $intxt;

    @_ >= 1 ? $intxt = shift : $intxt = '';

    $txt .= $intxt if ($intxt);

    my $body = _test_form_page( _wrap_http_env($id), $txt );
    my $doc  = $header . $body;

    return $doc;
} ## end sub _pack_test_psw

1

__END__
