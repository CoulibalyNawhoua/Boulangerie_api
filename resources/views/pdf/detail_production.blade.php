<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Boutique - Bon de livraison </title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <style>
        table{width : 100%; font-size : 15px;}
        .center{text-align:center;}
        .right{text-align:right;}
        .espace{padding : 1mm;}
        .tableau th{border : solid 1px #c6c4c6;}

        #invoice {
            border-collapse: collapse;

            width: 100%;
        }

        #invoice td, #invoice th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        #invoice th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }
        tr.sous-total, tr.total-ttc {
            background-color: #f5f5f5 !important;
            font-weight: bold;
        }
        #bodyFacture
        {
            margin: 15px auto;
            max-width: 850px;
            background-color: #fff;
            border: 1px solid #ccc;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            -o-border-radius: 6px;
            border-radius: 6px
        }
        button:hover {cursor: pointer !important;}
        .fl_left{
            float: left;
        }
        .fl_right{
            float: right;
        }
        .ftsz_12{
            font-size: 11px;
        }
        .ftsz_13{
            font-size: 13px;
        }

    </style>
</head>

<body>
<main>
    <table>
        <tbody>
            <tr>
                <td style="width:25%">
                    <div class="center">
                    <img src="#" alt="LOGO ICI" style="text-align:center;">
                    {{-- <div> <small class="ftsz_12"> Votre partenaire de vente de mèche humain </small> </div> --}}
                    </div>
                </td>
                <td style="width:50%">

                </td>
                <td style="width:25%">
                    <div class="fl_right">
                        <strong> {{Auth::user()->bakehouse?->name}} </strong>
                        <div class="ftsz_13"> {{Auth::user()->bakehouse?->phone}}  </div>
                        <div class="ftsz_13"> {{Auth::user()->bakehouse?->adress}}  </div>
                        {{-- <div class="ftsz_13"> {{$bakehouse?->tel}} </div> --}}
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
<hr style="margin-top:20px;"/>
    <table>
        <tbody>
            <tr>
                <td style="width:100%; padding: 10px 0px;">
                    <div class="center">
                    <div> <strong> Production </strong> </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="background-color: #ededed;padding: 5px" class="">
        <tr>
            <td style="width:70%">
                <p style="font-size: 12px">
                    <strong>DETAIL DE LA PRODUCTION   </strong> <br> <br>
                    {{-- <strong>N de bon :   </strong> {{$bonItem?->code}}<br> --}}

                    {{-- <strong>Reference : </strong>
                    {{$items?->reference }}  <br> --}}
                    {{-- <strong>Statut de l'achat : </strong>
                    @if ($items?->status == 1)
                        <b style="color: green">Livré</b>
                    @else
                        <b style="color: red">En attente</b>
                    @endif
                    <br> --}}

                    <strong> Date de production: </strong>
                    {{Carbon\Carbon::parse($items?->date)->locale('fr')->isoFormat('Do MMMM YYYY')}} <br>
                    <strong> Heure de production: </strong>
                    {{$items?->time}} <br>

                </p>
            </td>
            {{-- <td style="width:30%">
                <p style="font-size: 12px">
                    @if ($items->supplier)
                    <strong >INFORMATION DU FOURNISSEUR </strong> <br> <br>
                    <strong> Nom et Prénoms : </strong>
                    {{ucfirst($items?->supplier?->first_name) }} {{ucfirst($items?->supplier?->last_name) }}
                    <br>
                    <strong>Email : </strong>{{$items?->supplier?->email}}
                    <br>
                    <strong>Contact : </strong>{{$items?->supplier?->phone}}
                    <br>
                    <strong>Entreprise : </strong>{{$items?->supplier?->address}}
                    <br>
                    @endif

                </p>
            </td> --}}
        </tr>
    </table>
    <p style="font-size : 13px; padding: 20px 0px; text-align: center; margin: 0px"> <strong> </strong></p>
    @if ($items?->technical_sheet_details->count())
    <table id="invoice" style=" border : solid 1px #c6c4c6; font-size : 12px; margin-top: 0px !important;" class="tableau ">
        <thead>
        <tr>
            <th class="sorting" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="background:#ededed; width: 10%;" aria-label="Ship Country: activate to sort column ascending">ID</th>
            <th class="sorting" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="background:#ededed; width: 30%;" aria-label="Ship Country: activate to sort column ascending"> DESIGNATION</th>
            {{-- <th class="sorting" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="background:#ededed; width: 10%;" aria-label="Ship Country: activate to sort column ascending">PRIX UNITAIRE</th> --}}
            <th class="sorting" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="background:#ededed; width: 20%;" aria-label="Ship Address: activate to sort column ascending">QUANTITÉ</th>
            <th class="sorting" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="background:#ededed; width: 10%;" aria-label="Ship Country: activate to sort column ascending">UNITE</th>
        </tr>
        </thead>
        <tbody>
            @foreach($items->technical_sheet_details as $item)
                <tr role="row" class="odd">
                    <td>{{$loop->iteration }}<br/></td>
                    <td style="text-align : center">{{ $item->product->name ??'' }} {{--fcfa--}}</td>
                    {{-- <td style="text-align : center">  {{ number_format($item->unit_price ??0, 0, ' ', ' ')  }} FCFA </td> --}}
                    <td style="text-align : center">{{$item->quantity ?? 0}}</td>
                    <td style="text-align : right">{{ $item->unit?->name  }}</td>
                </tr>
            @endforeach

        {{-- <tr style="background:#ededed">
            <td colspan="4"><b>TOTAL QUANTITE ARTICLE </b></td>
            <td colspan="1" style="text-align : right"><b>{{ $bonItem?->factures->sum('quantite_mouv')  }}</b></td>
        </tr> --}}
        {{-- <tr style="background:#ededed">
            <td colspan="4"><b>SOUS TOTAL</b></td>
            <td colspan="1" style="text-align : right"><b>{{ number_format($items->total_amount ?? 0, 0, ' ', ' ')  }} FCFA</b></td>
        </tr>
        <tr style="background:#ededed">
            <td colspan="4"><b>TOTAL</b></td>
            <td colspan="1" style="text-align : right"><b>{{ number_format($items->total_amount ?? 0, 0, ' ', ' ') }} FCFA </b></td>
        </tr> --}}
        </tbody>
    </table>
    {{-- <p style="text-align: right;font-size : 12px; padding: 5px">TOTAL MEDICAMENT : {{$Nmedicaments}}</p> --}}
    @else
        <div>
            Aucun Produit!
        </div>
    @endif
</main>
</body>
</html>



