<h1>Newsletters</h1>
<p>Se encontraron {{ $subscriptions->total() }} suscripciones</p>
@if (!empty($message))
    <p class="alert feedback-{{ $message['type'] }}">{{ $message['value'] }}</p>
@endif
<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Usuario</th>
        <th>Status</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($subscriptions as $subscription)
        <tr>
            <td>{{ $subscription->id }}</td>
            <td>{{ $subscription->email }}</td>
            <td>{{ $subscription->user ? $subscription->user->name : 'Invitado'}}</td>
            <td>{{ $subscription->status }}</td>
            <td>
                <a href="{{ route('NewsletterAdminCancel', ['email' => $subscription->email]) }}" class="button default small" title="Cancelar Suscripción" data-confirm="¿Estás seguro que deseas cancelar la suscripción?">Cancelar</a>
                <a href="{{ route('NewsletterAdminDelete', ['email' => $subscription->email]) }}" class="button danger small" title="Borrar Suscripción" data-confirm="¿Estás seguro que deseas borrar la suscripción?">Borrar</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5">No hay secciones</td>
        </tr>
    @endforelse
    </tbody>
</table>
{{ $subscriptions->links() }}
<script type="text/javascript" src="{{asset('js/dashboard.js')}}"></script>
