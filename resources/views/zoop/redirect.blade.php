<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hundee E-signing</title>
</head>
<body>
    @if (request()->input('action') == 'esign-success')
        <div class="container">
            <div class="row px-4 py-5 my-5 text-center">
                <div class="col-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <img src="https://hundee.torzo.in/admin/dist/img/logo.png" alt="" height="60px" class="my-4 d-block mx-auto">
                    <h1 class="display-3 fw-bold mb-3">E-signing Successfull</h1>
                    <div class="col-lg-4 mx-auto">
                        <p class="lead" style="font-size: 22px;">Thank you for for your e-signature.</p>
                    </div>
                    <p style="font-size: 13px;">Find your signed document as attachment in your mail.</p>
                    <br>
                    <br>
                    <br>
                    <h6 class="text-uppercase">You can close this window</h6>
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <div class="row px-4 py-5 my-5 text-center">
                <div class="col-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <img src="https://hundee.torzo.in/admin/dist/img/logo.png" alt="" height="60px" class="my-4 d-block mx-auto">
                    <h1 class="display-3 fw-bold mb-3">E-signing Failure</h1>
                    <div class="col-lg-8 mx-auto">
                        <p class="lead">Please check your email within time limit to complete the E-signature process.</p>
                    </div>
                    <br>
                    <br>
                    <br>
                    <h6 class="text-uppercase">You can close this window</h6>
                </div>
            </div>
        </div>
        {{-- <div class="px-4 py-5 my-5 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>

            <h1 class="display-5 fw-bold">E-signing Failure</h1>
            <div class="col-lg-6 mx-auto">
                <p class="lead mb-4">Please check your email within time limit to complete the E-signature process.</p>

                <img src="https://hundee.torzo.in/admin/dist/img/logo.png" alt="" height="30px" class="my-4">
            </div>
        </div> --}}
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>