<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SpamFree;
use App\Reply;

class CreateReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // note because of how policies work you always
        // need to pass in whatever class the policy belongs to
        // even if it is a blank object
        return \Gate::allows('create', new Reply);
    }


    /**
     * override the Form Requests failed autoriziation method
     * with our own custom logic.
     * Note we could throw a custom error here.
     *
     * @return \Illuminate\Http\RedirectResponse;
     */
    protected function failedAuthorization()
    {
        return back()->withErrors('You are posting too frequently, please wait a bit');
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required', app(SpamFree::class)]
        ];
    }
}
