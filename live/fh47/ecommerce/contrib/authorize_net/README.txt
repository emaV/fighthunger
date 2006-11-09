Payment Module  : AUTHORIZE.NET
Original Author : Matt Westgate
Settings        : > administer > store > settings > authorize_net 

********************************************************************
DESCRIPTION:

Accept payments using Authorize.net. This module uses the Advanced
Integration Method (AIM) to submit transactions to the payment
gateway.

This module was written for a pair.com server and the way they
offer SSL hosting. Your mileage may very, but I've tried to
document everything I had to do to get it up and running.

********************************************************************
SPECIAL NOTES:

You must have curl compiled with php.

This module does not work using shared SSL certificates. So for
example https://ssl20.pair.com/username/ will not work is this is
not your base_url as configured in includes/conf.php. However if
your base_url is http://www.example.com/ and the SSL URL is
https://www.example.com/, this module will function as expected.
You must obtain a secure server certificate from a Certificate
Authority ("CA") to have this functionality.

********************************************************************

See MAINTAINERS.txt for more maintenance info.
See README.txt (in E-Commerce root) for other info.