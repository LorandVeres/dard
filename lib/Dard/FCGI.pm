#!/usr/bin/perl
package Dard::FCGI;

use 5.30.0;
use strict;
use warnings;

our $VERSION = '0.00.01';

use lib '/home/' . getpwuid($<) . '/.local/share/perl/';

use Exporter qw/import/;

our @EXPORT_OK = qw(
    $FCGI
    FCGI
    fcgi_response
    fcgi_request
);

our $FCGI = {

    # Number of bytes in a FCGI_Header.  Future versions of the protocol
    # will not reduce this number.
    HEADER_LEN => 8,

    # protocol version
    VERSION_1 => 1,

    # Mask for flags component of FCGI_BeginRequestBody
    KEEP_CONN => 1,

    # request role
    RESPONDER  => 1,
    AUTHORIZER => 2,
    FILTER     => 3,

    # Values for protocolStatus component of FCGI_EndRequestBody
    REQUEST_COMPLETE => 0,
    CANT_MPX_CONN    => 1,
    OVERLOADED       => 2,
    UNKNOWN_ROLE     => 3,

    # packet type
    BEGIN_REQUEST     => 1,
    ABORT_REQUEST     => 2,
    END_REQUEST       => 3,
    PARAMS            => 4,
    STDIN             => 5,
    STDOUT            => 6,
    STDERR            => 7,
    DATA              => 8,
    GET_VALUES        => 9,
    GET_VALUES_RESULT => 10,
    UNKNOWN_TYPE      => 11
};

sub FCGI
{
    my ( $self, $param ) = @_;
    $FCGI->{$param} if ( defined $FCGI->{$param} );
}

sub fcgi_request
{
    my ( $request_id, $env, $content ) = @_;
    my $flags = 0;
    return join( '',
        _request_begin( $request_id, $FCGI->{RESPONDER}, $flags ),
        _request_params( $request_id, $env ),
        _request_params($request_id),
        ( $content ? _request_stdin( $request_id, $content ) : '' ),
        _request_stdin( $request_id, '' ) );
}

sub fcgi_response
{

    my ($ref) = shift;
    my $request_type;
    my $app_status;
    my $protocol_status;
    my $req_id  = $ref->{request_id};
    my $content = $ref->{content};
    my $params  = $ref->{env};

    defined( $ref->{request_type} )    ? $request_type    = $ref->{request_type}    : $request_type    = $FCGI->{END_REQUEST};
    defined( $ref->{app_status} )      ? $app_status      = $ref->{app_status}      : $app_status      = 0;
    defined( $ref->{protocol_status} ) ? $protocol_status = $ref->{protocol_status} : $protocol_status = $FCGI->{REQUEST_COMPLETE};

    return join( '',
        ( defined( $ref->{env} )     ? _request_params( $req_id, $params )   : '' ),
        ( defined( $ref->{content} ) ? _response_stdout( $req_id, $content ) : '' ),
        _request_base( $req_id, $request_type, _response_end( $ref->{request_id}, $app_status, $protocol_status ) ) );

} ## end sub fcgi_response

# Generic request accepts any text/binary data as content
sub _request_base
{
    my ( $request_id, $type, $content ) = @_;

    #   typedef struct {
    #       unsigned char version;
    #       unsigned char type;
    #       unsigned char requestIdB1;
    #       unsigned char requestIdB0;
    #       unsigned char contentLengthB1;
    #       unsigned char contentLengthB0;
    #       unsigned char paddingLength;
    #       unsigned char reserved;
    #       unsigned char contentData[contentLength];
    #       unsigned char paddingData[paddingLength];
    #       } FCGI_Record;
    my $make_record = sub
    {
        my $txt = $_[0]        || '';
        my $len = length($txt) || 0;

        # C = An unsigned char (1 octet = 8 bits) value
        # n = An unsigned short (16-bit) in "network" (big-endian) order.
        pack( 'CCnnCC', $FCGI->{VERSION_1}, int $type, $request_id, $len, 0, 0, ) . $txt;
    };

    if ( $content && length($content) > 0 ) {
        my $buffer = '';
        while ( length($content) > 65535 ) {
            $buffer .= $make_record->( substr( $content, 0, 65535 ) );
            $content = substr( $content, 65535 );
        }
        $buffer .= $make_record->($content);
        return $buffer;
    } else {

        # Create an empty record
        return $make_record->('');
    }
} ## end sub _request_base

# FCGI_BEGIN_REQUEST record
sub _request_begin
{
    my ( $request_id, $role, $flags ) = @_;

    #   typedef struct {
    #       unsigned char roleB1;
    #       unsigned char roleB0;
    #       unsigned char flags;
    #       unsigned char reserved[5];
    #       } FCGI_BeginRequestBody
    my $content = pack( 'nCCCCCC', $role, $flags, 0, 0, 0, 0, 0 );

    _request_base( $request_id, $FCGI->{BEGIN_REQUEST}, $content );
}

# FCGI_PARAMS record
sub _request_params
{
    my ( $req_id, $params ) = @_;
    my $content = '';

    # All PARAMS records need a final empty record
    my $empty_record = pack( 'CCnnCC', $FCGI->{VERSION_1}, $FCGI->{PARAMS}, $req_id, 0, 0, 0, );

    my $pack_params_length = sub
    {
        my ( $val, $content ) = @_;
        my $len = length($val);

        if ( $len < 127 ) {

            # An unsigned char (octet) value.
            $content .= pack( 'C', $len );
        } else {

            # An unsigned long (32-bit) in "network" (big-endian) order.
            $content .= pack( 'N', $len | 2147483647 );
        }
        return $content;
    };

    while ( my ( $key, $val ) = each %{$params} ) {
        $content .= $pack_params_length->($key);
        $content .= $pack_params_length->($val);
        $content .= $key;
        $content .= $val;
    }

    _request_base( $req_id, $FCGI->{PARAMS}, $content ) . $empty_record;
} ## end sub _request_params

sub _request_stdin
{
    my ( $req_id, $content ) = @_;

    # All STDIN records need a final empty record
    my $empty_record = pack( 'CCnnCC', $FCGI->{VERSION_1}, $FCGI->{STDIN}, $req_id, 0, 0, 0, );

    $content ||= '';
    length($content) > 0 ? _request_base( $req_id, $FCGI->{STDIN}, $content ) . $empty_record : $empty_record;
}

sub _response_stdout
{
    my ( $req_id, $content ) = @_;

    # All STDOUT records need a final empty record
    my $empty_record = pack( 'CCnnCC', $FCGI->{VERSION_1}, $FCGI->{STDIN}, $req_id, 0, 0, 0, );

    $content ||= '';
    length($content) > 0 ? _request_base( $req_id, $FCGI->{STDOUT}, $content ) . $empty_record : $empty_record;
}

sub _response_end
{
    my ( $request_id, $appStatus, $protocolStatus ) = @_;
    _request_base( $request_id, $FCGI->{END_REQUEST}, _response_end_body( $appStatus, $protocolStatus ) );
}

# Used in the above function to generate the end body
sub _response_end_body
{
    my ( $appStatus, $protocolStatus ) = @_;
    my ( $app, $protocol );
    $app      = $appStatus      || 0                         if ( !$appStatus );
    $protocol = $protocolStatus || $FCGI->{REQUEST_COMPLETE} if ( !$protocolStatus );

    #   typedef struct {
    #       unsigned char appStatusB3;
    #       unsigned char appStatusB2;
    #       unsigned char appStatusB1;
    #       unsigned char appStatusB0;
    #       unsigned char protocolStatus;
    #       unsigned char reserved[3];
    #   } FCGI_EndRequestBody;
    pack( 'NCCCC', $app, $protocol, 0, 0, 0 );
} ## end sub _response_end_body

1

__END__
