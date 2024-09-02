<pre>
-----------------------------------------------------
                   AuctionApp
-----------------------------------------------------
                 AUCTION RESULTS
-----------------------------------------------------
We regret to inform you that you have lost the auction
for the item {{ $presenter->getProductName() }} at {{ $presenter->getBidAmount() }},
which was won by another bidder ({{ $presenter->getDiff() }} diff).
Better luck next time!.

-----------------------------------------------------
Contact Us:
Customer Service: support@auctionapp.com
Terms and Conditions Apply.

Thank you for your participation!

-----------------------------------------------------
</pre>

<a href="{{$presenter->getBidUrl()}}">Review the auction</a>

Thanks,<br>
{{ config('app.name') }}
