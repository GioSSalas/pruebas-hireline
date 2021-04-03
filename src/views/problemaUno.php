<html>

<head>
    <title>Problema Uno Hireline - Neubox</title>

    <link rel="stylesheet" href="css/style.css" />
    <script type="text/javascript" src="./js/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="root">
        <div class="solver-frame">
            <h2>Problema 1</h2>

            <button class="file-uploader" id="uploaderButton">
                <span>Subir archivo de entrada</span>
            </button>
            <form action="" method="post" id="uploadForm">
                <input type="file" name="file"  />
            </form>
            <div id="loading" class="loading">
                <div class="loading-spin"></div>
            </div>
            <div id="message" class="message"></div>
            <div id="content" class="result">
                
            </div>
            <div id="result" class="result">
                
            </div>
        </div>
    </div>
    <script type="text/javascript" src="./js/script.js" ></script>
</body>

</html>