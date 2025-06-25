@props([
    'colspan' => null
])

<tbody class="fw-bold text-gray-600">
<tr>
    <td colspan="{{ $colspan }}">
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </td>
</tr>
</tbody>
