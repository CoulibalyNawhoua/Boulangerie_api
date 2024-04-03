<html>
<head>

    <title>BOULANGERIE</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <style>
        .info-asso:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            /* background-image: url('/home/fpmpro/forest/static/img/Logo.jpg'); */
            background-position: center;
            background-size:500px;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
            opacity: .3;
        }
        table.GeneratedTable {
            /* background-image: url('/home/fpmpro/forest/static/img/Logo.jpg'); */
            background-position: center;
            background-size:500px;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
            opacity: .3;
            background-color: #ffffff;
            border-collapse: collapse;
            border-width: 2px;
            border-color: #ffcc00;
            border-style: solid;
            color: #000000;
            font-size: 13px;
        }

        table.GeneratedTable td, table.GeneratedTable th {
            border-width: 2px;
            border-color: black;
            border-style: solid;
            padding: 3px;
        }

        table.GeneratedTable thead {
            background-color: #525252;
            color: white;
        }
        table.table2{
            width: auto;
            background-color: #ffffff;
            border-collapse: collapse;
            border-width: 2px;
            border-color: #ffcc00;
            border-style: solid;
            color: white;
            font-size: 13px;
        }
        table.table2 td{
            border-width: 2px;
            border-color: black;
            border-style: solid;
            padding: 5px;
            font-weight: bold;
        }
        table.table2 tr td:nth-child(1){
            background-color: #757474;
            color: white;
        }
        table.table2 tr td:nth-child(2), table.table1 tr td:nth-child(2){
            text-align: center;
        }
        table.table1{
            width: 50%;
            background-color: #ffffff;
            border-collapse: collapse;
            border-width: 2px;
            border-color: #ffcc00;
            border-style: solid;
            color: #000000;
            font-size: 13px;
        }
        table.table1 td{
            /* border-width: 2px; */
            /* border-color: black; */
            border-style: solid;
            padding: 3px;
            font-weight: bold;
        }
        .text-danger{
            color: #f13030;
        }
        #footer {
            position: relative;
            margin-top: 700px;
        }
        .float-right{
            float: right;
            margin-right: 0 !important;
        }
        .fw-b{
            font-weight: bold;
            right: 0;
        }
        .tb-type-coti thead tr {
            background-color: grey;
            border: 1px solid black;
            color: white;
            text-align: left;
        }
        .tb-type-coti tr td{
            border-width: 2px;
            border-color: #030303;
        }
        .tfoot td{
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body class="info-asso">

<hr style="">
<h3 style="text-align: center; text-decoration: underline"><strong><span>  LISTE DES FOURNISSEURS</span></strong></h3>

<table class="table table-striped GeneratedTable" border="0.1" style="text-align: center; height:20px; padding-top: 2px; padding-bottom: -5px;">
    <thead style="margin-top: 15px">
        <tr style="margin-top: 15px;">
            <th style="width: 20%">NOM</th>
            <th style="width: 20%">PRENOMS</th>
            <th style="width: 21% ; margin-top: 15px">EMAIL</th>
            <th style="width: 21% ; margin-top: 15px">TELEPHONE</th>
            <th style="width: 10%; margin-top: 15px">ENTREPRISE</th>
            <th style="width: 10%; margin-top: 15px">ADRESSE</th>
        </tr>
    </thead>
    <tbody >
        @foreach ($items as $item)
                <tr >
                    <td> {{$item->first_name}}</td>
                    <td>{{$item->last_name}}</td>
                    <td>{{$item->email}}</td>
                    <td> {{$item->phone}}</td>
                    <td> {{$item->compagny}}</td>
                    <td>{{$item->address}}</td>

                </tr>
        @endforeach


    </tbody>
</table>



{{-- <div id="footer" style="text-align: center; width: 100%">
    <hr style="color: red">
    <h5 style="font-style: italic; text-align: center;">
        Siège social :  cooperative.siege       <br/>
         PRODUCTEURS  cooperative  - Copyright(c) - AGRO-MAP
    </h5>
</div> --}}

</body>
</html>
