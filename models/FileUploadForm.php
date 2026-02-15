<?php

namespace app\models;

use yii\base\Model;


class FileUploadForm extends Model
{
    public $files; // Массив файлов

    public function rules()
    {
        return [
            [['files'], 'file', "skipOnEmpty" => true,  'maxFiles' => 2, "extensions" => "pdf"], // Максимум 2 файлa
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $savedFiles = [];
            if ($this->files) {
                foreach ($this->files as $file) {
                    $fileName = \Yii::$app->security->generateRandomString() . '.' . $file->extension;
                    $filePath = \Yii::getAlias('@webroot/upload/') . $fileName;

                    if ($file->saveAs($filePath)) {
                        $savedFiles[] = [
                            'name' => $file->name,
                            'path' => $filePath,
                            'size' => $file->size,
                            'type' => $file->type,
                        ];
                    }
                }
            }
            return $savedFiles;
        } else {
            return false;
        }
    }
}
