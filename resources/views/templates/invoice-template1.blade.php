<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Invoice {{$number}}</title>
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        line-height: 24px;
        font-family: 'Times-new-roman', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
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
        color: rgb(67, 163, 175);
        padding-left:20px;
        font-family: "Times New Roman"
    }

    </style>
</head>


<body>
    <br><br>
    <table style="width: 100%;" >
        <tr>
            <td class="logo">INVOICES</td>
            <td colspan="2" style="text-align: right;padding: 20px">Date :{{$created_at}}<br>Invoices : #245245
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
                {{ $customer['name'] }}<br />
                {{ $customer['phone'] }}<br />
                {{ $customer['addresses'][0]['city'] }}<br />
                {{ $customer['addresses'][0]['country'] }}<br />
                {{ $customer['addresses'][0]['address'] }}<br />
                {{ $customer['addresses'][0]['zip_code'] }}
            </td>
        </tr>
    </table>

    <div class="invoice-box" style="margin-top: -80px">
        <table cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <td colspan="4" style="background-color: transparent ;height: 85px"></td>
            </tr>
            <tr class="heading">
                <td class="invoice-label ">Description</td>
                <td class="invoice-label ">Prix Unitaire</td>
                <td class="invoice-label ">Quantité</td>
                <td class="invoice-label ">Total</td>
            </tr>
            </thead>
            @foreach ($lines as $line)
            <tr class="item" style="background-color: rgba(255, 255, 255, 0.233)">
                <td>{{ $line['description'] }}</td>
                <td style="text-align:right">{{ $line['price'] }}</td>
                <td style="text-align:right">{{ $line['quantity'] }}</td>
                <td style="text-align:right">{{ $line['quantity'] * $line['price'] }}</td>
            </tr>
            @endforeach
            <tr><td style="background-color: transparent ;height: 30px"></td></tr>
            <tr class=>
                <td></td>
                <td></td>
                <td class="invoice-label">Sub-Total</td>
                <td style="text-align:right">${{$total_excltax}}</td>
            </tr>
            <tr class=>
                <td></td>
                <td></td>
                <td class="invoice-label">Tax</td>
                <td style="text-align:right">${{$tax_total}}</td>
            </tr>
            <tr class=" right-block">
                <td></td>
                <td></td>
                <td class="invoice-label">Total</td>
                <td style="text-align:right">${{$total_incltax}}</td>
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

