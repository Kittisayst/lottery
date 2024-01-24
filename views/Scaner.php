<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="d-flex justify-content-center flex-column align-items-center" id="content">
        <h3 class="text-center my-2" id="lblTitle">Scanner</h3>
        <div id="reader" class="col-12 col-sm-10 col-md-8 col-lg-4 col-lx-3 d-flex flex-column"></div>
        <div class="col-12 col-sm-10 col-md-8 col-lg-4 col-lx-3 d-flex flex-column align-items-center" id="actionContent" hidden>
            <input type="text" id="txtcode" class="form-control fs-5 text-success text-center fw-bold">
            <div class="d-flex gap-2 mt-2 w-100">
                <button class="btn btn-primary w-100" id="btncopy"><i class='bx bxs-copy-alt'></i> Copy</button>
                <button class="btn btn-success w-100" id="btnreload"><i class='bx bx-scan' ></i> New Scan</button>
            </div>
        </div>
    </div>

    <script src="../script/html5-qrcode.min.js"></script>
    <script>
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 15,
                qrbox: 250
            });

            $("#txtcode").hide();
            $("#btncopy").hide();
            $("#btnreload").hide();



        function onScanSuccess(decodedText, decodedResult) {
            // Handle on success condition with the decoded text or result.
            console.log(`Scan result: ${decodedText}`, decodedResult);
            const content = document.getElementById("content");
            const reader = document.getElementById("reader");
            const actionContent = document.getElementById("actionContent");
            const input = document.getElementById("txtcode");
            const btncopy = document.getElementById("btncopy");
            const btnreload = document.getElementById("btnreload");
            actionContent.removeAttribute("hidden");
            input.value = `${decodedText}`;

            $("#txtcode").show();
            $("#btncopy").show();
            $("#btnreload").show();


            // reload
            btnreload.addEventListener("click", () => {
                location.reload();
            });
            btncopy.addEventListener("click", () => {
                input.select();
                input.setSelectionRange(0, 99999);
                document.execCommand("copy");
                alert("Copied: " + input.value);
            });


            // ...
            html5QrcodeScanner.clear();
            content.removeChild(reader);
            // ^ this will stop the scanner (video feed) and clear the scan area.
        }

        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>

</html>