<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $name;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
   	    $this->name = md5(rand()) . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs(Yii::$app->basePath . '/web/uploads/' . $this->name);
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels(){
        return ["imageFile" => "Upload Image"];
    }
}

?>
