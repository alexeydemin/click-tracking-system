<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Clixxa Test</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,400,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 400;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
				font-weight: 100;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

			ul {
				text-align:left;
			}
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Clixxa Laravel/PHP Test
                </div>
				<h2>Overview</h2>
				
				<p>
				Migrations have been setup to create a basic database structure. Once run the database will include:
				<ul>
					<li>migrations - default laravel migrations table</li>
					<li>users - default laravel users table</li>
					<li>password_resets - default laravel password reset table</li>
					<li>folders - a representation of an advertisers folder</li>
					<li>placements - a representation of a publishers placements</li>
					<li>clicks - a purchased click.  The placement makes money from a click, the folder spends money to buy clicks.
				</ul>
				</p>

				<h2>Instructions</h2>
				<div style="text-align:left">
					<p>Your task is given a user, create a financial transaction history for each day for the data in the clicks table.  All folder_costs are negative and all placement_costs are positive.  Essentially money is transferring from the folder user to the placement holder.</p>
					<p>Two endpoints should be created, one that returns the results for a user in json, the other that displays a simple page in html.</p>
					<p>There is a caveat to the data that is stored - all money values in the database are stored as pennies * 1000 so 18388 is $0.18388.  All values for transactions should be returned in $0.00 format, but data loss should be minimized.</p>
					<p>A row in a transaction must include: Amount Debit, Amount Credit, Balance at time of transaction<p>
					<p>A total for all transactions must also be included.</p>
					<p>Click data is continually being added, and can be added for any day in the past.  Since there can be millions of clicks on the live system, design a system that can handle this.  Transaction records can be at most 10 minutes behind the live click data<p>
					<br><br>
					<p>Along with the code, please provide a document that outlines your design decisions, and any implications they may have.  This task is essentially a blank slate, so have fun, and be creative.</p>
				</div>
				
            </div>
        </div>
    </body>
</html>
