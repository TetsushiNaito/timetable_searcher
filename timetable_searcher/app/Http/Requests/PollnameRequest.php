<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PollnameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ( $this->path() == 'confirm' ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'depr_poll' => 'required|pollname',
            'dest_poll' => 'required|pollname',
        ];
    }
    public function messages() {
        return [
            'depr_poll.required' => '出発地のバス停を入力してください',
            'dest_poll.required' => '目的地のバス停を入力してください',
            'depr_poll.pollname' => '正しいバス停名を入力してください',
            'dest_poll.pollname' => '正しいバス停名を入力してください',
        ];
    }

}
