<pre>
-----------------------------------------------------
                   AuctionApp
-----------------------------------------------------
                  NEW BID PLACED
                AuctionID: {{ $presenter->productIdDisplay() }}
             Date of bid: {{ $presenter->bidAt() }}
-----------------------------------------------------
Auction Item Details:
Item Name: {{ $presenter->productName() }}
Item ID: {{ $presenter->productIdDisplay() }}

-----------------------------------------------------
Bidding Details:
Bid Amount: {{ $presenter->bidAmount() }}
Bid Difference: {{ $presenter->diffAmount() }}
Bidding Date: {{ $presenter->bidAt() }}

-----------------------------------------------------
Contact Us:
Customer Service: support@auctionapp.com
Terms and Conditions Apply.

Thank you for your participation!

-----------------------------------------------------
</pre>

<a href="{{$presenter->bidUrl()}}">Place New Bid</a>

Thanks,<br>
{{ config('app.name') }}
