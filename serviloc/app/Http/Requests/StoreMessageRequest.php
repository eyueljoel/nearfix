<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ServiceRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $serviceRequest = ServiceRequest::find($this->service_request_id);
        
        if (!$serviceRequest) {
            return false;
        }

        // User must be a participant in the service request
        // Either the customer who created it or the assigned provider
        $isParticipant = $serviceRequest->user_id === auth()->id() ||
                        $serviceRequest->assigned_provider_id === auth()->id();

        if (!$isParticipant) {
            return false;
        }

        // Recipient must be the OTHER party (not self)
        $recipient = $this->recipient_id;
        $isValidRecipient = ($serviceRequest->user_id === $recipient && $serviceRequest->assigned_provider_id === auth()->id()) ||
                           ($serviceRequest->assigned_provider_id === $recipient && $serviceRequest->user_id === auth()->id());

        return $isValidRecipient;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_request_id' => 'required|exists:service_requests,id',
            'recipient_id' => 'required|exists:users,id|different:'.auth()->id(),
            'body' => 'required|string|min:1|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'service_request_id.required' => 'Service request is required.',
            'service_request_id.exists' => 'Invalid service request.',
            'recipient_id.required' => 'Recipient is required.',
            'recipient_id.exists' => 'Invalid recipient.',
            'recipient_id.different' => 'You cannot message yourself.',
            'body.required' => 'Message cannot be empty.',
            'body.min' => 'Message must contain at least 1 character.',
            'body.max' => 'Message cannot exceed 2000 characters.',
        ];
    }
}
