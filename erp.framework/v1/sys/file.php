<?php
namespace Sys\File {
    define('FILE_SERIALIZE',FILE_NO_DEFAULT_CONTEXT << 3);
    define('FILE_JSON',FILE_SERIALIZE << 1);
    define('FILE_GZIP',FILE_JSON << 1);

    function ReadFile($FilePathName,$Flags=null,$DefaultContent=null) {
        $Content = file_get_contents(\Path($FilePathName));
        ($Flags & FILE_GZIP) && !empty($Content) && ($Content = gzdecode($Content));
        ($Flags & FILE_JSON) && !empty($Content) && ($Content = json_decode($Content,true));
        ($Flags & FILE_SERIALIZE) && !empty($Content) && ($Content = unserialize($Content));
        return !empty($Content) ? $Content : $DefaultContent;
    }

    function WriteFile($FilePathName,$Content,$Flags=null) {
        MkDir(dirname($FilePathName));
        ($Flags & FILE_JSON) ? ($Content = json_encode($Content,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) : (($Flags & FILE_SERIALIZE) && ($Content = serialize($Content)));
        return file_put_contents(\Path($FilePathName), ($Flags & FILE_GZIP) ? gzencode($Content,9) : $Content, ($Flags & (~(FILE_GZIP|FILE_JSON|FILE_SERIALIZE))));
    }

    /**
     * ��������� ��������� ����� $_FILES �� ������ � ��������� ����������. ������� ������������ ���� ����� ��������� ���������� ���, ��� ���� ����� ������ ���� ���������� � ����������� � ���������� �����.
     * @param array $FilesArray ������ $_FILES � ��������� ���������� �.�. $_FILES['files']
     * @param string $UploadDirectoryPath ���������� � ������� ����� ��������� �����. ���� ���������� �� ���������� �� ����� �������.
     * @param array|null $AllowedTypes ������ ����������� ����� ������ (������ ���������� (.ext) ������)
     * @param int $MaxFileSize ������������ ������ ������ �����
     * @param string $UploadFileNamePrefix �������, ������� ����� �������� �� ���������� ����� ������� ������������ �����
     * @param function|null $UploadHandler ������� ������� ����� ���������� ��� ������� ������������ �����, ���� ������� false, �� ���� ����� �������� � �� �������� �� ������. function($name,$type,$tmp_name,$error,$size,$ext) {}
     * @return array ������������� ������ ['name'=>[],'type'=>[],'tmp_name'=>[],'error'=>[],'size'=>[],'uid'=>[],'ext'=>[]] ������ uid[] ����� ��������� ���������� ������������� ������������ �����, ����� ���� null, ���� ���� ����� �������� (������, �� ������������ ����, �������� ������ ���� �������� � $UploadHandler)
     */
    function Upload($FormVarName,$UploadDirectoryPath,$AllowedTypes=null,$MaxFileSize=0,$UploadFileNamePrefix='',$UploadHandler=null) {
        if(!isset($_FILES[$FormVarName])) {

            MkDir($UploadDirectoryPath,true);

            $FilesList = [];
            $UploadedFilesList = [];

            is_array($_FILES[$FormVarName]['name']) ? ($FilesList = $_FILES[$FormVarName]) :
                ($FilesList = ['name'=>[$_FILES[$FormVarName]['name']],'type'=>[$_FILES[$FormVarName]['type']],'tmp_name'=>[$_FILES[$FormVarName]['tmp_name']],'error'=>[$_FILES[$FormVarName]['error']],'size'=>[$_FILES[$FormVarName]['size']]]);

            for($i=0;$i<count($FilesList);$i++) {
                $FilesList['ext'][$i] = mb_strtolower(pathinfo($FilesList['name'][$i],PATHINFO_EXTENSION));
                $FilesList['uid'][$i] = null;
                $FileUid = $UploadFileNamePrefix.date('YmdHis').rand(1000,9999).'-'.sha1($FilesList['name'][$i]);


                if(!is_uploaded_file($FilesList['tmp_name'][$i])) {
                    $FilesList['error'][$i] = UPLOAD_ERR_NO_FILE;
                }

                if(is_callable($UploadHandler)) {
                    if(call_user_func($UploadHandler, $FilesList['name'][$i], $FilesList['type'][$i], $FilesList['tmp_name'][$i], $FilesList['error'][$i], $FilesList['size'][$i], $FilesList['ext'][$i]) === false) {
                        $FilesList['error'][$i] = UPLOAD_ERR_NO_FILE; continue;
                    }
                }

                if($FilesList['error'][$i] <> UPLOAD_ERR_OK) {
                    continue;
                }

                if($MaxFileSize && intval($FilesList['size'][$i]) > intval($MaxFileSize)) {
                    $FilesList['error'][$i] = UPLOAD_ERR_INI_SIZE; continue;
                }
                if(!empty($AllowedTypes) && !in_array($FilesList['ext'][$i], $AllowedTypes)) {
                    $FilesList['error'][$i] = UPLOAD_ERR_EXTENSION; continue;
                }

                if(!move_uploaded_file($FilesList['tmp_name'][$i], \Path(\Dir($UploadDirectoryPath).$FileUid))) {
                    $FilesList['error'][$i] = UPLOAD_ERR_CANT_WRITE; continue;
                }

                $FilesList['uid'][$i] = $FileUid;

                $UploadedFilesList[] = ['name'=>basename($FilesList['name'][$i]),'type'=>$FilesList['type'][$i],'size'=>$FilesList['size'][$i],'uid'=>$FilesList['uid'][$i],'ext'=>$FilesList['ext'][$i]];

                WriteFile(\Directory($UploadDirectoryPath).'.'.$FileUid,['name'=>basename($FilesList['name'][$i]),'type'=>$FilesList['type'][$i],'size'=>$FilesList['size'][$i],'uid'=>$FilesList['uid'][$i],'ext'=>$FilesList['ext'][$i]],FILE_JSON|FILE_GZIP);
            }

            return $UploadedFilesList;
        }

        return [];
    }

    function MetaInfo($FileUid,$DirectoryPathName) {
        return preg_match('/[\w-]+/', $FileUid) ? ReadFile(\Directory($DirectoryPathName).'.'.$FileUid,FILE_JSON|FILE_GZIP) : false;
    }

    function DownloadContent($Name,$Content,$Mime,$Headers=[]) {
        while(ob_get_level()) ob_end_clean();
        is_array($Headers) && !empty($Headers) && array_map('header', $Headers);
        header("Content-Type: ".$Mime);
        header('Content-Disposition: attachment; filename="'.$Name.'";filename*=utf-8\'\''.rawurlencode($Name),true);
        @print($Content);
        exit;
    }

    function Download($FileUid,$UploadDirectoryPath,$DownloadFileName=null) {
        if(($MetaInfo = MetaInfo($FileUid,$UploadDirectoryPath)) === false || !file_exists($FilePath = \Path(\Directory($UploadDirectoryPath).$FileUid))) {
            header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found',true,404);
            exit;
        }
        $Name = empty($DownloadFileName) ? $MetaInfo['name'] : ($DownloadFileName.(!empty($MetaInfo['ext'])?('.'.$MetaInfo['ext']):''));
        while(ob_get_level()) ob_end_clean();
        header('Content-Disposition: attachment; filename="'.$Name.'";filename*=utf-8\'\''.rawurlencode($Name),true);
        header("Content-Length: " . filesize($FilePath));
        @readfile($FilePath);
        exit;
    }

    function DownloadFiles($FileUids,$UploadDirectoryPath,$ArchiveName='archive.zip') {
        if(!empty($FileUids)) {
            $Path = \Path(\Directory($UploadDirectoryPath));

            $zip = new \ZipArchive(); $zipFile = tempnam('tmp', "temp-zip-");
            $zip->open($zipFile, \ZipArchive::OVERWRITE | \ZipArchive::CREATE );

            foreach($FileUids as $uid) {
                $uid = trim($uid);
                if(($MetaInfo = MetaInfo($uid,$UploadDirectoryPath)) === false) continue;
                $zip->addFile($Path.$MetaInfo['uid'],$MetaInfo['name']);
            }
            while(ob_get_level()) ob_end_clean();
            header("Content-Type: application/zip");
            header("Content-Length: " . filesize($zipFile));
            header('Content-Disposition: attachment; filename="'.$ArchiveName.'";filename*=utf-8\'\''.rawurlencode($ArchiveName),true);
            @readfile($zipFile);
            @$zip->close();
            @unlink($zipFile);
        }
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found',true,404);
        exit;
    }

    /**
     * ������� ����������
     * @param string $DirectoryPathName ��� ����������. ������ ������������ ����������� �������� ����������.
     * @param bool|string $htaccess ��������� � ��������� ���������� ���� .htaccess. ���� true - �� .htaccess = 'deny from all' , ���� ������� ������, �� ����� ��������� �� ����������, ���� false, .htaccess ����������� �� �����
     * @param int $Mode ����� ��������������� �� ����������, �� ��������� 0644
     * @return bool
     */
    function MkDir($DirectoryPathName,$htaccess=false,$Mode=0774) {
        !($_res = file_exists($DirectoryPathName = \Path($DirectoryPathName))) && ($_res = \mkdir($DirectoryPathName, $Mode, true) && !empty($htaccess) && file_put_contents(\Directory($DirectoryPathName).'.htaccess', ($htaccess === true ? 'deny from all':$htaccess)));
        return $_res;
    }

    /**
     * ��������� ������������� �����
     * @param string $FilePathName
     */
    function Exists($FilePathName) {
        return file_exists(\Path($FilePathName));
    }

    /**
     * ���������� ����� ������� �����
     * @param string $FilePathName
     * @param int|null $TouchTime ������������� ����� ������� �����, ���� �� null @see(touch)
     * @param int|null $ATime ������������ ����� ������� � �����
     * @return int
     */
    function Time($FilePathName,$TouchTime=null,$ATime=null) {
        !is_null($TouchTime) && touch(\Path($FilePathName), $TouchTime, $ATime);
        return filemtime(\Path($FilePathName));
    }
}
