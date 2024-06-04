<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Invoice {{$invoice->number}}</title>
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding-bottom: 30px;
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
        background: #eee;
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
        color: #007efd;
    }
    .invoice-label {
        color: #007efd;
        width: 150px;

    }
    .headerstyle
    {
            line-height: 36px;
            background-color: #f1f1f1;
            height: 50px;
            color: rgb(75, 124, 243);
            font-weight: bold;
            font-size: 16px;
    }

    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100 %;
            display: block;
            text-align: center;
        }
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    .rtl table {
        text-align: right;
    }
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }

    .cardlabel
    {
        font-weight: bold;font-size: 18px;color: rgb(75, 124, 243)
    }
    </style>
</head>


<body>
<div style="background-color: rgb(243, 243, 243);padding: 20px">
    <span style="font-weight: bold;font-size: 18px;color: rgb(75, 124, 243)">FACTURE F-2021-00736</span><br>
    Date : {{$invoice->created_at}}<br>
    Échéance : 31/07/2021<br>
    Responsable : Support Manageo
</div>

<div class="row" style="display: flex;align-content: center;padding: 40px">
    <div class="col" style="padding: 20px;margin-right: 30px">
        <div class="cardlabel">Bussiness details :</div>
        Beweb Inc<br />
        Technopark Casablanca, bureau 429<br />
        contact@beweb.ma<br />
        (+212) 5 22 21 58 30
    </div>

    <div class="col" style="background-color: rgb(243, 243, 243);border : 2px rgb(238, 238, 238) solid;padding: 20px">

        <div class="cardlabel">Customer details :</div>
        {{$customer->name}}<br />
        {{$customer->phone}}<br />
        {{-- {{$customer->city}}<br />
        {{$customer->country}}<br />
        {{$customer->address}}<br />
        {{$customer->zip_code}} --}}
        Casablanca<br>
        Morocco<br>
        454 Hay Maarif<br>
        8000<br>
    </div>

</div>

    <div class="invoice-box">
        <div class="cardlabel">Items :</div><br>
        <table cellpadding="0" cellspacing="0">
            <thead>
            <tr class="headerstyle">
                <td>Description</td>
                <td>Prix Unitaire</td>
                <td >Quantité</td>
                <td >Total</td>
            </tr>
            </thead>
            @foreach ($lines as $line)
            <tr class="item">
                <td>{{$line->description}}</td>
                <td style="text-align:right">{{$line->price}}</td>
                <td style="text-align:right">{{$line->quantity}}</td>
                <td style="text-align:right"> {{$line->quantity*$line->price}}</td>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
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
    </div>

    <div style="background-color: rgb(243, 243, 243);padding: 20px">
        <span  class="cardlabel">Note :</span><br>
    ARRÊTÉ LA PRÉSENTE FACTURE À LA SOMME DE :
    Seize mille huit cent dirhams toutes taxes comprises
    </div>

</body>
</html>

