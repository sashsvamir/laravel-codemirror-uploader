<?php
namespace Sashsvamir\LaravelCodemirrorUploader;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UploadableRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'model_alias' => 'required|string|max:255',
            'model_id' => 'required|integer',
            'action' => ['required', Rule::in(['get', 'upload', 'delete'])],
        ];

        if ($this->get('action') === 'upload') {
            $rules['file'] = 'required|file';
        }

        if ($this->get('action') === 'delete') {
            $rules['files'] = 'required|array';
            $rules['files.*'] = 'required|string';
        }

        return $rules;
    }

    public function getModelAlias(): string
    {
        return $this->get('model_alias');
    }

    public function getModelId(): int
    {
        return $this->get('model_id');
    }

    public function getAction(): string
    {
        return $this->get('action');
    }

}
