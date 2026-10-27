@props(['value' => 'pending'])
@php($normalized = strtolower((string) $value))
<span @class(['record-status', 'is-success' => in_array($normalized, ['completed','paid','active','1']), 'is-danger' => in_array($normalized, ['cancelled','inactive','0']), 'is-pending' => !in_array($normalized, ['completed','paid','active','1','cancelled','inactive','0'])])><span aria-hidden="true"></span>{{ __(ucfirst($normalized === '1' ? 'active' : ($normalized === '0' ? 'inactive' : $normalized))) }}</span>
