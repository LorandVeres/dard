use 5.30.0;
use ExtUtils::MakeMaker;
use utf8;

# See lib/ExtUtils/MakeMaker.pm for details of how to influence
# the contents of the Makefile that is written.
WriteMakefile(
    NAME    => 'Dard',
    VERSION => '0.00.02',

    INSTALLDIRS => 'site',    # force istall into site perl, not vendor or core
    PREREQ_PM   => {

        # 'One::Module' => '6.44',
        #' Two'           => '2.97',
    },

    EXE_FILES => [ ('bin/psw') ],

    #INSTALLSITEBIN => 'bin',

    #INST_SCRIPT => 'bin',
    ABSTRACT => "Dard project bundle",
    AUTHOR   => 'Lorand Veres <lorand@dard.dev>',
);
