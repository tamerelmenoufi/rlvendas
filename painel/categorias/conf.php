<?php
    //Config
    $ConfTitulo = 'Categorias de Produtos';
    $UrlScript = 'categorias/';
    //Config ----------

    function getSituacao()
    {
        return [
            '1' => 'Liberado',
            '0' => 'Bloqueado',
        ];
    }



    function uploadIcone($codigo)
    {
        global $_FILES;
        global $_POST;

        file_put_contents('debug.txt', json_encode($_FILES['file']['tmp_name']));
        $targetDir = 'icon';
        $fileBlob = 'file';

        if (!file_exists($targetDir) and isset($_FILES[$fileBlob])) {
            @mkdir($targetDir);
        }

        if (isset($_FILES[$fileBlob])) {
            $file = $_FILES[$fileBlob]['tmp_name'];
            $fileName = $_POST['fileName'];
            $fileSize = $_POST['fileSize'];
            $fileId = $_POST['fileId'];
            $targetFile = $targetDir . '/' . $codigo . '.jpg';

            if (move_uploaded_file($file, $targetFile)) {
                return 'ok';
            } else {
                return [
                    'error' => 'Error uploading '
                ];
            }
        }
        return [
            'error' => 'No file found'
        ];

    }