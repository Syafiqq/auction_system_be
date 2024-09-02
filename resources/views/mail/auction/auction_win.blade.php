<pre>
-----------------------------------------------------
                   AuctionApp
-----------------------------------------------------
                 CONGRATULATIONS!
-----------------------------------------------------
Congratulations! You have won the auction for the item
{{ $presenter->getProductName() }} at the price of
{{ $presenter->getBidAmount() }}.

-----------------------------------------------------
                    Details
-----------------------------------------------------
Auction Item Details:
Item Name: {{ $presenter->getProductName() }}
Item ID: {{ $presenter->getProductId() }}

Bidding Details:
Bid Amount: {{ $presenter->getBidAmount() }}
Bidding Date: {{ $presenter->getBidAt() }}

-----------------------------------------------------

                Final Amount Due:
                {{ $presenter->getBidAmount() }}

-----------------------------------------------------
**Payment Information:**
- **Accepted Methods:** Credit Card, PayPal,
  Bank Transfer
- **Payment Due Date:** {{ $presenter->getBillDueAt() }}

**Overdue Warning:**
- **Status:** Payment is overdue if not received by
  the due date.
- **Penalty:** Late payments may incur a fee of
  $10 or 5% of the total amount due (whichever is higher).
- **Final Payment Deadline:** {{ $presenter->getBillDueAt() }}
  after which the auction item may be forfeited.

-----------------------------------------------------
Contact Us:
Customer Service: support@auctionapp.com
Terms and Conditions Apply.

Thank you for your participation!

-----------------------------------------------------
</pre>

<a href="{{$presenter->getBidUrl()}}">Review the auction</a>
<a href="{{$presenter->getPaymentUrl()}}">Finish your payment</a>

Thanks,<br>
{{ config('app.name') }}
