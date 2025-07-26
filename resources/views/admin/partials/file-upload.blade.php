<form method="POST" action="/upload-media">
    <input type="file" multiple class="filepond" name="image[]" id="image-upload">
    <input type="hidden" name="image_ids" id="image-ids">
</form>
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet">
<!-- FilePond scripts -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>

<script>
// Register plugins
FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType
);

// Get a reference to the file input element
const inputElement = document.querySelector('#image-upload');

// Create FilePond instance
const pond = FilePond.create(inputElement, {
    allowMultiple: true,
    allowImagePreview: true,
    acceptedFileTypes: ['image/*'],
    server: {
        url: window.location.origin,
        process: {
            url: '/upload-media',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            ondata: (formData) => {
                formData.append('model_id', {{ $product->id }});
                return formData;
            },
            onload: (response) => {
                const data = JSON.parse(response);
                const currentIds = document.getElementById('image-ids').value;
                const newIds = currentIds ? `${currentIds},${data.media_id}` : data.media_id;
                document.getElementById('image-ids').value = newIds;
                
                // Return BOTH the media ID and URL as metadata
                return {
                    uniqueFileId: data.media_id,
                    metadata: {
                        media_id: data.media_id,
                        url: data.url
                    }
                };
            }
        },
        revert: (uniqueFileId, load, error) => {
            // Get the actual media ID from the file object
            const file = pond.getFile(uniqueFileId);
            if (!file) {
                console.log('File not found');
                return;
            }
            
            const mediaId = file.getMetadata('media_id');
            if (!mediaId) {
                console.log('Media ID not found');
                return;
            }
            
            fetch(`/delete-media/${mediaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    model_id: {{ $product->id }}
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Delete failed');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove from hidden field
                    const currentIds = document.getElementById('image-ids').value.split(',');
                    const updatedIds = currentIds.filter(id => id !== mediaId).join(',');
                    document.getElementById('image-ids').value = updatedIds;
                    load();
                }
            })
            .catch(err => {
                console.error('Delete error:', err);
                error(err);
            });
        }
    }
});

// Load existing images with proper metadata
@if($product->hasMedia('images'))
    @foreach($product->getMedia('images') as $media)
        pond.addFile('{{ $media->getUrl() }}', {
            type: 'limbo',
            file: {
                name: '{{ $media->file_name }}',
                size: {{ $media->size }},
                type: '{{ $media->mime_type }}'
            },
            // Set metadata with media ID
            metadata: {
                media_id: '{{ $media->id }}'
            },
            // Set serverId to media ID
            serverId: '{{ $media->id }}'
        });
    @endforeach
    document.getElementById('image-ids').value = '{{ $product->getMedia('images')->pluck('id')->implode(',') }}';
@endif
</script>
