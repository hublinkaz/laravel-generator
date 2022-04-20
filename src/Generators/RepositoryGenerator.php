<?php

namespace Hublinkaz\Generator\Generators;

use Illuminate\Support\Str;
use Hublinkaz\Generator\Utils\FileUtil;
use Hublinkaz\Generator\Common\CommandData;

class RepositoryGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $fileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathRepository;
        $this->fileName = $this->commandData->modelName . 'Repository.php';
    }

    public function generate()
    {
        //

        $rep_data = ['$data= new ' . $this->commandData->modelName . '();'];

        $update_data = ['$data = $this->' . $this->commandData->modelName . 'Repository->find($id);'];
        $delete_data = ['$data = $this->' . $this->commandData->modelName . 'Repository->find($id);'];

        foreach ($this->commandData->fields as $field) {
            if ($field->name != 'id' && $field->name != 'created_at' && $field->name != 'updated_at') {
                if (Str::endsWith($field->name, '_tr')) {
                    $rep_data[] = ' ';
                    $rep_data[] = '//------------------- ' . $field->name . ' ----------------- ';
                    $rep_data[] = ' ';
                    $rep_data[] = '$data->setTranslation("' . $field->name . '", $request->lang, $request->' . $field->name . ');';
                }

                if ($field->htmlType == 'file') {
                    $rep_data[] = ' ';
                    $rep_data[] = '//------------------- ' . $field->name . ' ----------------- ';
                    $rep_data[] = ' ';

                }

                if (!Str::endsWith($field->name, '_tr') && $field->htmlType != 'file') {
                    $rep_data[] = ' ';
                    $rep_data[] = '//------------------- ' . $field->name . ' ----------------- ';
                    $rep_data[] = ' ';
                    $rep_data[] = '$data->' . $field->name . '= $request->' . $field->name . ';';
                }
            }
        }
        $rep_data[] = 'CreateImage($request,"' . $this->commandData->modelName . '",$data->id,"image");';

        $rep_data[] = '$data->save();';

        $rep_data[] = 'return $data;';



        foreach ($this->commandData->fields as $field) {
            if ($field->name != 'id' && $field->name != 'created_at' && $field->name != 'updated_at') {
                if (Str::endsWith($field->name, '_tr')) {
                    $update_data[] = ' ';
                    $update_data[] = '//------------------- ' . $field->name . ' ----------------- ';
                    $update_data[] = ' ';
                    $update_data[] = '$data->setTranslation("' . $field->name . '", $request->lang, $request->' . $field->name . ');';
                }

                if ($field->htmlType == 'file') {
                    $update_data[] = ' ';
                    $update_data[] = '//------------------- ' . $field->name . ' ----------------- ';
                    $update_data[] = ' ';

                }

                if (!Str::endsWith($field->name, '_tr') && $field->htmlType != 'file') {
                    $update_data[] = ' ';
                    $update_data[] = '//------------------- ' . $field->name . ' ----------------- ';
                    $update_data[] = ' ';
                    $update_data[] = '$data->' . $field->name . '= $request->' . $field->name . ';';
                }
            }
        }





        foreach ($this->commandData->fields as $field) {
            if ($field->name != 'id' && $field->name != 'created_at' && $field->name != 'updated_at') {

                if ($field->htmlType == 'file') {
                    $delete_data[] = ' ';
                    $delete_data[] = '//------------------- ' . $field->name . ' ----------------- ';
                    $delete_data[] = ' ';

                }
            }
        }

        $delete_data[] = 'return $data->delete();';

        $rep_data[] = 'CreateImage($request,"' . $this->commandData->modelName . '",$data->id,"image");';
        $update_data[] = '$data->save();';
        $update_data[] = 'return $data;';

        $templateData = get_template('repository_file', 'laravel-generator');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        $searchables = [];

        foreach ($this->commandData->fields as $field) {
            if ($field->isSearchable) {
                $searchables[] = "'" . $field->name . "'";
            }
        }

        $templateData = str_replace('$REP$', implode('' . infy_nl_tab(1, 2), $rep_data), $templateData);
        $templateData = str_replace('$REP_UP$', implode('' . infy_nl_tab(1, 2), $update_data), $templateData);
        $templateData = str_replace('$REP_DEL$', implode('' . infy_nl_tab(1, 2), $delete_data), $templateData);




        $templateData = str_replace('$FIELDS$', implode(',' . infy_nl_tab(1, 2), $searchables), $templateData);

        $docsTemplate = get_template('docs.repository', 'laravel-generator');
        $docsTemplate = fill_template($this->commandData->dynamicVars, $docsTemplate);
        $docsTemplate = str_replace('$GENERATE_DATE$', date('F j, Y, g:i a T'), $docsTemplate);

        $templateData = str_replace('$DOCS$', $docsTemplate, $templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nRepository created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Repository file deleted: ' . $this->fileName);
        }
    }
}
