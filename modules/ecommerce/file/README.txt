Product Module  : FILE
Original Author : Matt Westgate
Settings        : > administer > store > settings > file

********************************************************************
DESCRIPTION:

Creates file download products for ecommerce.

********************************************************************
USAGE:

The customer will acces the files they paid for by clicking on the
'my files' link in their navigation block after they login.  You
should probably add these instructions to the e-mail users receive
after making a purchase. The default instructions are here:
> administer > store > settings > payment

Customers are be able to download files after the payment status
has been marked 'completed' for their transaction. Typically this
happens immediately after payment, but in cases of e-checks and
other payment types, this could be a couple of days.

If you are using cron on your site, transactions that consist
only of non-shippable items, such as file downloads,  will have
their workflow be moved from 'pending' to 'completed'. This saves
you the time of doing this manually.

********************************************************************

See MAINTAINERS.txt for more maintenance info.
See README.txt (in E-Commerce root) for other info.







