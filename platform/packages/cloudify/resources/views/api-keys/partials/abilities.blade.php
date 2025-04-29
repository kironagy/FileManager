<x-core::form.fieldset>
    <div class="mb-3">
        <div class="form-label">{{ trans('packages/cloudify::cloudify.api_key.abilities') }}</div>
        <div>
            <div
                data-bb-collapse="true"
                data-bb-trigger="[name='type']"
                data-bb-value="external"
                @style(['display: none' => $apiKey->type == 'internal'])
            >
                @foreach($externalAbilities as $ability => $label)
                    <x-core::form.checkbox
                        name="abilities[]"
                        :label="$label"
                        :value="$ability"
                        :checked="true"
                        :label_attr="['class' => 'mb-2']"
                    />
                @endforeach
            </div>

            <div
                data-bb-collapse="true"
                data-bb-trigger="[name='type']"
                data-bb-value="internal"
                @style(['display:none' => $apiKey->type == 'external'])
            >
                @foreach($internalAbilities as $ability => $label)
                    <x-core::form.checkbox
                        name="abilities[]"
                        :label="$label"
                        :value="$ability"
                        :checked="true"
                        :label_attr="['class' => 'mb-2']"
                    />
                @endforeach
            </div>
        </div>
    </div>
</x-core::form.fieldset>
