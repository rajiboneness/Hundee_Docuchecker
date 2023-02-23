<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>ZOOP e-sign for Co-Borrower</title>
    </head>
    <body>
        <div id="zoop-gateway-model">
            <div id="zoop-model-content"></div>
        </div>

        <script src="{{ asset('admin/zoop-sdk.min.js') }}"></script>

        <script type="application/javascript">
            function openGateway() {
                zoop.eSignGatewayInit({
                    mode: "REDIRECT", // default TAB/ REDIRECT, but choose either of them
                    zoomLevel: 1, // Default: 7, integer between 1 to 7. PDF viewer zoom level.
                });
                // Pass the transaction ID created at Init call
                zoop.eSignGateway("{{$response->request_id}}");
            }
            openGateway();
        </script>
    </body>
</html>