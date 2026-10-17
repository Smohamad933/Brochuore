<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Event details
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'slug' => [
                'required',
                'string',
                'min:'.config('negah.slug_min', 3),
                'max:'.config('negah.slug_max', 40),
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::notIn(Event::RESERVED_SLUGS),
                Rule::unique('events', 'slug'),
            ],
            'event_type' => ['required', Rule::in(array_keys(Event::EVENT_TYPES))],
            'output_type' => ['required', Rule::in(array_keys(Event::OUTPUT_TYPES))],
            'event_date' => ['required', 'date', 'after:now'],
            'venue' => ['required', 'string', 'min:3', 'max:500'],
            'description' => ['required', 'string', 'min:10', 'max:20000'],

            // Visual identity
            'logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'brand_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'template_name' => ['required', Rule::in(array_keys(Event::TEMPLATES))],

            // Contact
            'contact_phone' => ['required', 'string', 'min:5', 'max:40'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.regex' => 'The link may only contain lowercase letters, numbers and dashes (e.g. my-event-2026).',
            'slug.not_in' => 'That link is reserved by the platform. Please choose another one.',
            'slug.unique' => 'That link is already taken. Please choose another one.',
            'event_date.after' => 'The event date must be in the future.',
            'brand_color.regex' => 'The primary brand colour must be a valid HEX colour such as #1D4ED8.',
            'secondary_color.regex' => 'The secondary colour must be a valid HEX colour such as #F59E0B.',
            'logo.mimes' => 'The logo must be a PNG, JPG, JPEG or WEBP image.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'slug' => 'custom link',
            'event_type' => 'event type',
            'output_type' => 'output type',
            'event_date' => 'event date',
            'brand_color' => 'primary brand colour',
            'secondary_color' => 'secondary colour',
            'template_name' => 'template',
            'contact_phone' => 'contact phone',
        ];
    }
}
