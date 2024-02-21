@extends('layout')

@section('styles')
    <style>
        .drag-handle {
            cursor: move; /* Changes the cursor to indicate movability */
        }
        .sortable-chosen { /* Class added by SortableJS to the dragged element */
            background-color: #f0f0f0 !important; /* Temporary background color when dragging */
        }
    </style>
@endsection

@section('content')
    <h1>Modes de passation</h1>
    <a href="{{ route('mode-passations.create') }}" class="btn btn-primary mt-1 mb-1"
        >Nouveau mode de passation
    </a>
    <form id="rank-update" action="{{ route('mode-passations.updateRank') }}" method="POST">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th>Changer le rang</th>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="sortable">
                @foreach ($modePassations as $modePassation)
                    <tr data-id="{{ $modePassation->id }}">
                        <td class="drag-handle">≡</td> 
                        <td>{{ $modePassation->id }}</td>
                        <td>{{ $modePassation->name }}</td>
                        <td>{{ $modePassation->description }}</td>
                        <td>
                            <a href="{{ route('mode-passations.edit', $modePassation->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $modePassation->id }}"
                                onclick="confirmDeletion({{ $modePassation->id }})">
                                    Supprimer
                            </button>
                        </td>
                        <input type="hidden" name="ranks[]" value="{{ $modePassation->id }}">
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row justify-content-center">
            <button type="submit" form="rank-update" class="btn btn-success btn-sm">Mettre à jour le rang</button>
        </div>
    </form>
@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
    var el = document.getElementById('sortable');
    var sortable = Sortable.create(el, {
        animation: 150,
        store: {
            /**
             * Get the order of elements. Called once during initialization.
             * @param   {Sortable}  sortable
             * @returns {Array}
             */
            get: function (sortable) {
                var order = localStorage.getItem(sortable.options.group.name);
                return order ? order.split('|') : [];
            },

            /**
             * Save the order of elements. Called onEnd (when the item is dropped).
             * @param {Sortable}  sortable
             */
            set: function (sortable) {
                var order = sortable.toArray();
                localStorage.setItem(sortable.options.group.name, order.join('|'));

                // Update the hidden inputs based on the new order
                order.forEach(function(id, index) {
                    // Assuming the hidden input's value should now reflect the new rank
                    $('tr[data-id="' + id + '"]').find('input[type="hidden"]').val(index);
                });
            }
        },
        // Element dragging ended
        onEnd: function (/**Event*/evt) {
            var item = evt.item; // the current dragged HTMLElement
            // You could also potentially update the hidden inputs here if needed
        },
    });
</script>

<script>
function confirmDeletion(id) {
    if(confirm('Are you sure?')) {
        // Create a form dynamically and submit it for deletion
        var form = document.createElement('form');
        form.action = `/mode-passations/${id}`;
        form.method = 'post';

        // CSRF token is necessary for Laravel to process the form
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';

        // Method input to specify the HTTP verb (DELETE)
        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);

        form.submit();
    }
}
</script>

@endsection