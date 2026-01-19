use strict;
use warnings;

use Test::More;       # Declares how many tests to expect
use Test::use::ok;    # Use the helper module

# Check if a module loads correctly
use_ok('FCGI');

# A basic check (passes)
ok( 1 + 1 == 2, 'Addition works' );

# A failing check (for demonstration)
# ok(1 + 1 == 3, 'Addition fails'); # Would show 'not ok 1...'

done_testing();
