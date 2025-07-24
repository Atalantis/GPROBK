@props(['customFields', 'model' => null])

@if($customFields->isNotEmpty())
    <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Champs Personnalisés</h3>

    @foreach ($customFields as $field)
        @php
            $value = $model ? $model->customFields->where('custom_field_definition_id', $field->id)->first()?->value : '';
        @endphp
        <div class="mt-4">
            <x-input-label :for="'custom_field_' . $field->id" :value="$field->name" />

            @if ($field->type === 'text')
                <x-text-input :id="'custom_field_' . $field->id" class="block mt-1 w-full" type="text" :name="'custom_fields[' . $field->id . ']'" :value="old('custom_fields.' . $field->id, $value)" />
            @elseif ($field->type === 'number')
                <x-text-input :id="'custom_field_' . $field->id" class="block mt-1 w-full" type="number" :name="'custom_fields[' . $field->id . ']'" :value="old('custom_fields.' . $field->id, $value)" />
            @elseif ($field->type === 'date')
                <x-text-input :id="'custom_field_' . $field->id" class="block mt-1 w-full" type="date" :name="'custom_fields[' . $field->id . ']'" :value="old('custom_fields.' . $field->id, $value)" />
            @elseif ($field->type === 'select')
                <select :id="'custom_field_' . $field->id" :name="'custom_fields[' . $field->id . ']'" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                    <option value="">-- Choisir --</option>
                    @foreach ($field->options as $option)
                        <option value="{{ $option }}" @selected(old('custom_fields.' . $field->id, $value) == $option)>{{ $option }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    @endforeach
@endif
