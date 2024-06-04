<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Invoice {{$invoice->number}}</title>
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        /* border: 3px solid black; */
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
        line-height: 24px;
        font-family: 'Times-new-roman', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        /* border: 3px solid black; */
    }
    .left-block {
        text-align: left;
    }
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    .invoice-box table tr td:nth-child(2) {
        /* text-align: right; */
    }
    .invoice-box table tr.top table td {
        padding-bottom: 20px;

    }
    .invoice-box table tr.top table td.title {
        font-size: 30px;
        line-height: 45px;
        color: #333;
    }
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    .invoice-box table tr.heading td {
        background: rgb(247, 247, 247);
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    .total-invoice {
        font-size: 20px;
        /* line-height: 45px; */
        color: #222222;
    }
    .invoice-label {
        color: #222222;
        font-size: 15px;
        font-weight: bold;

    }

    .logo
    {
        font-size:40px;
        font-weight: bold;
        color: #ff4f4f;
        padding-left:20px;
        font-family: "Times New Roman"
    }

    body{
        background-color: #f4b0b0;
background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 800 800'%3E%3Cg fill='none' stroke='%23ef9f9f' stroke-width='1'%3E%3Cpath d='M769 229L1037 260.9M927 880L731 737 520 660 309 538 40 599 295 764 126.5 879.5 40 599-197 493 102 382-31 229 126.5 79.5-69-63'/%3E%3Cpath d='M-31 229L237 261 390 382 603 493 308.5 537.5 101.5 381.5M370 905L295 764'/%3E%3Cpath d='M520 660L578 842 731 737 840 599 603 493 520 660 295 764 309 538 390 382 539 269 769 229 577.5 41.5 370 105 295 -36 126.5 79.5 237 261 102 382 40 599 -69 737 127 880'/%3E%3Cpath d='M520-140L578.5 42.5 731-63M603 493L539 269 237 261 370 105M902 382L539 269M390 382L102 382'/%3E%3Cpath d='M-222 42L126.5 79.5 370 105 539 269 577.5 41.5 927 80 769 229 902 382 603 493 731 737M295-36L577.5 41.5M578 842L295 764M40-201L127 80M102 382L-261 269'/%3E%3C/g%3E%3Cg fill='%23fca2a2'%3E%3Ccircle cx='769' cy='229' r='5'/%3E%3Ccircle cx='539' cy='269' r='5'/%3E%3Ccircle cx='603' cy='493' r='5'/%3E%3Ccircle cx='731' cy='737' r='5'/%3E%3Ccircle cx='520' cy='660' r='5'/%3E%3Ccircle cx='309' cy='538' r='5'/%3E%3Ccircle cx='295' cy='764' r='5'/%3E%3Ccircle cx='40' cy='599' r='5'/%3E%3Ccircle cx='102' cy='382' r='5'/%3E%3Ccircle cx='127' cy='80' r='5'/%3E%3Ccircle cx='370' cy='105' r='5'/%3E%3Ccircle cx='578' cy='42' r='5'/%3E%3Ccircle cx='237' cy='261' r='5'/%3E%3Ccircle cx='390' cy='382' r='5'/%3E%3C/g%3E%3C/svg%3E");
      }

    </style>
</head>


<body>
<br><br>
    <table style="width: 100%;" >
        <tr>
            <td class="logo">INVOICES</td>
            <td colspan="2" style="text-align: right;padding: 20px">Date :{{$invoice->created_at}}<br>Invoices : #245245
            </td>
        </tr>
    </table>

    <br><br>

        <table style="width: 100%;background-color: rgba(255, 255, 255, 0.137)">
        <tr>
            <td style="padding-left:20px">
                        <span class="invoice-label">Business Details</span> <br><br>
                        <span>Beweb Inc</span> <br />
                        <span>Technopark Casablanca, bureau 429</span><br />
                        <span> contact@beweb.ma</span><br />
                        <span> (+212) 5 22 21 58 30</span><br />
            </td>
            <td colspan="2" style="text-align: right;padding: 20px">

                        <span class="invoice-label">Customer Details</span><br><br>
                        {{$customer->name}}<br />
                        {{$customer->phone}}<br />
                        {{$customer->city}}<br />
                        {{$customer->country}}<br />
                        {{$customer->address}}<br />
                        {{$customer->zip_code}}
            </td>
        </tr>
    </table>

    <div class="invoice-box" style="margin-top: -80px">
        <table cellpadding="0" cellspacing="0">
            <thead>
            <tr><td style="background-color: transparent ;height: 85px"></td></tr>
            <tr class="heading">
                <td class="invoice-label ">Description</td>
                <td class="invoice-label ">Prix Unitaire</td>
                <td class="invoice-label ">Quantité</td>
                <td class="invoice-label ">Total</td>
            </tr>
            </thead>
            @foreach ($lines as $line)
            <tr class="item" style="background-color: rgba(255, 255, 255, 0.233)">
                <td>{{$line->description}}</td>
                <td style="text-align:right">{{$line->price}}</td>
                <td style="text-align:right">{{$line->quantity}}</td>
                <td style="text-align:right"> {{$line->quantity*$line->price}}</td>
            </tr>
            @endforeach
            <tr><td style="background-color: transparent ;height: 30px"></td></tr>
            <tr class=>
                <td></td>
                <td></td>
                <td class="invoice-label">Sub-Total</td>
                <td style="text-align:right">${{$invoice->sub_total}}</td>
            </tr>
            <tr class=>
                <td></td>
                <td></td>
                <td class="invoice-label">Tax</td>
                <td style="text-align:right">${{$invoice->tax_total}}</td>
            </tr>
            <tr class=" right-block">
                <td></td>
                <td></td>
                <td class="invoice-label">Total</td>
                <td style="text-align:right">${{$invoice->total_incltax}}</td>
            </tr>
        </table>

        <br>
        <table style="width: 100%;">
            <tr style="background-color: rgba(255, 255, 255, 0.137)">
                <td style="padding-left:20px">
                            <span class="invoice-label">Notes :</span> <br><br>
                            <span>Please pay your invoice by...</span> <br />
                </td>
            </tr>
        </table>

    </div>


</body>
</html>

