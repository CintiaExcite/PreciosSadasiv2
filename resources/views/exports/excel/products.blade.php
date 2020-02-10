<table>
    <thead>
    <tr>
        <th><b>ID</b></th>
        <th><b>Estatus</b></th>
        <th><b>Nombre</b></th>
        <th><b>Estado</b></th>
        <th><b>Desarrollo</b></th>
        <th><b>Precio</b></th>
        <th><b>Descuento</b></th>
        <th><b>Ingresos mensuales desde</b></th>
        <th><b>Pagos mensuales desde</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->status == 1 ? 'Activo' : 'Inactivo' }}</td>
            <td>{{ $product->product }}</td>
            <td>{{ $product->development->state->state }}</td>
            <td>{{ $product->development->development }}</td>
            <td>{{ $product->price->price }}</td>
            <td>{{ $product->discount->discount }}</td>
            <td>{{ $product->income->income_from }}</td>
            <td>{{ $product->payment->payments_from }}</td>
        </tr>
    @endforeach
    </tbody>
</table>