<form wire:submit="saveEnclosure" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="content-header mb-3">
        <h3 class="mb-0">{{ __('enclosure.title') }}</h3>
        <small>{{ __('enclosure.subtitle_stip') }}</small>
    </div>
    <div class="row g-3">
        <x-notification />

        <h4 class="mb-0">{{ __('enclosure.remark') }}</h4>
        <div class="col-sm-12">
            <div class="row g-3">
                <div class="col-sm-12">
                    <textarea wire:model.blur="enclosure.remark" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <br />
            <br />
            <h4 class="mb-0">{{ __('enclosure.reqDocs') }}</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('enclosure.doc') }}</th>
                            <th scope="col">{{ __('enclosure.file') }}</th>
                            <th scope="col">{{ __('enclosure.upload') }}</th>
                            <th scope="col">{{ __('enclosure.send_later') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (['certificate_of_study', 'tax_assessment', 'expense_receipts', 'parents_tax_factors'] as $index => $field)
                            <x-enclosure-upload-row :rowNumber="$index + 1" :fieldName="$field" :isRequired="true"
                                :model="$field" :enclosure="$enclosure" />
                        @endforeach

                        @foreach (['partner_tax_assessment', 'supplementary_services', 'ects_points'] as $index => $field)
                            <x-enclosure-upload-row :rowNumber="$index + 5" :fieldName="$field" :model="$field"
                                :enclosure="$enclosure" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-success">
                <span class="align-middle d-sm-inline-block d-none">{{ __('attributes.save') }}</span>
            </button>
        </div>
    </div>
</form>
