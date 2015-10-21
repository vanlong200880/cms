<?php
namespace Sky\Uploads;
class Upload{
    public function uploadImage($fileName, $filePath, $fileType = ''){
        $adapter = new \Zend\File\Transfer\Adapter\Http();
        $info = pathinfo($fileName);
        $ext = $info['extension']? ".".$info['extension']:"";
        $newName = $info['filename']. '-' . time(). $ext;
        $adapter->setDestination($filePath);
        var_dump($adapter->getDestination());
        $adapter->addFilter('File\Rename',
            array(
                'target' => $adapter->getDestination().'/'.$newName,
                'overwrite' => true));
        if($adapter->receive($fileName)){
            return $newName;
        }
    }
}
