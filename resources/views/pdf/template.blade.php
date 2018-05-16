<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('titulo')</title>
    <style>
        .page-break {
            page-break-after: always;
        }
		header{
			text-align:center;
			margin-bottom: 0.5cm;
		}
		header img{
			height: 2cm;
			width: 2cm;
		}
		header p{
			font-size: 14px;
			font-weight: bold;
			margin: 2px 0;
		}
		h1{
			font-size: 18px;
			margin-bottom: 0.3cm;
			text-align: center;
			text-transform: uppercase;
		}
		h2{
			font-size: 16px;
			margin-bottom: 0.3cm;
			text-align: center;
		}
		h3{
			font-size: 14px;
			margin-bottom: 0.3cm;
			text-align: center;
		}
		h4{
			font-size: 14px;
			margin-bottom: 0.3cm;
			text-align: center;
			border-top: 1px solid #5F7D19;
			border-bottom: 1px solid #5F7D19;
			background-color: #D7D9C8;
			font-weight: bold;
		}
		hr{
			color: #5F7D19;
		}
		table{
			width: 100%;
			border-collapse: collapse;
		}
		table th{
			background-color: #FAFCEA;
            text-align: center;
		}
		table th, table td{
			border: 1px solid black;
			padding: 2px 5px;
			text-transform: uppercase;
            font-size: 12px;
		}
		.centralizar{
			text-align:center;
		}
        .erro{
            color: red;
        }
		footer{
			position: absolute;
			bottom: 0;
		}
		footer .direita{
			display: inline-block;
			vertical-align: bottom;
			width: 45%;
			text-align: right;
			float: right;
		}
		footer .esquerda{
			display: inline-block;
			vertical-align: bottom;
			width: 45%;
		}
    </style>
</head>
<body>
    @yield('corpo')
</body>
</html>