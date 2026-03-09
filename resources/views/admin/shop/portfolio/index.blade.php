@extends('admin.layouts.master')
@section('title', __('Portfolio Management'))
@section('content')

    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <div class="pagetitle">
                    <h1 class="h3 fw-bold mb-2">{{ $pageTitle }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            @foreach ($breadcrumbs as $breadcrumb)
                                @if (!$breadcrumb['active'])
                                    <li class="breadcrumb-item">
                                        <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                                            {{ $breadcrumb['label'] }}
                                        </a>
                                    </li>
                                @else
                                    <li class="breadcrumb-item active text-muted" aria-current="page">
                                        {{ $breadcrumb['label'] }}
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <small class="text-muted fw-semibold">
                    {{ __('messages.ip_address') }}:
                </small>
                <span class="fw-semibold text-primary">
                    {{ auth()->user()->ip_address }}
                </span>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form action="{{ route('portfolio.update', $portfolio->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3 mb-4">

                        {{-- Personal Information --}}
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted fw-semibold small border-bottom pb-2 mb-1">
                                Personal Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-semibold small">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name"
                                name="full_name" value="{{ old('full_name', $portfolio->full_name) }}"
                                placeholder="e.g. Jane Doe" maxlength="100" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label fw-semibold small">
                                Role / Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('role') is-invalid @enderror" id="role"
                                name="role" value="{{ old('role', $portfolio->role) }}"
                                placeholder="e.g. Full-Stack Developer" maxlength="100" required>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="location" class="form-label fw-semibold small">
                                Location <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location"
                                name="location" value="{{ old('location', $portfolio->location) }}"
                                placeholder="e.g. Phnom Penh, Cambodia" maxlength="100" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="experience_yrs" class="form-label fw-semibold small">
                                Experience (Years) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('experience_yrs') is-invalid @enderror"
                                id="experience_yrs" name="experience_yrs"
                                value="{{ old('experience_yrs', $portfolio->experience_yrs) }}" placeholder="e.g. 5" min="0"
                                max="255" required>
                            @error('experience_yrs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Professional Background --}}
                        <div class="col-12 mt-2">
                            <h6 class="text-uppercase text-muted fw-semibold small border-bottom pb-2 mb-1">
                                Professional Background
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label for="education" class="form-label fw-semibold small">
                                Education <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('education') is-invalid @enderror" id="education"
                                name="education" value="{{ old('education', $portfolio->education) }}"
                                placeholder="e.g. B.Sc. Computer Science" maxlength="150" required>
                            @error('education')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="focus_area" class="form-label fw-semibold small">
                                Focus Area <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('focus_area') is-invalid @enderror"
                                id="focus_area" name="focus_area" value="{{ old('focus_area', $portfolio->focus_area) }}"
                                placeholder="e.g. AI / Machine Learning" maxlength="150" required>
                            @error('focus_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Contact --}}
                        <div class="col-12 mt-2">
                            <h6 class="text-uppercase text-muted fw-semibold small border-bottom pb-2 mb-1">
                                Contact
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $portfolio->email) }}" placeholder="jane@example.com"
                                maxlength="150" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Social Links --}}
                        <div class="col-12 mt-2">
                            <h6 class="text-uppercase text-muted fw-semibold small border-bottom pb-2 mb-1">
                                Social Links
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label for="github_url" class="form-label fw-semibold small">
                                GitHub <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text ">
                                    <i class="bi bi-github"></i>
                                </span>
                                <input type="url" class="form-control @error('github_url') is-invalid @enderror"
                                    id="github_url" name="github_url"
                                    value="{{ old('github_url', $portfolio->github_url) }}"
                                    placeholder="https://github.com/username" maxlength="255">
                                @error('github_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="linkedin_url" class="form-label fw-semibold small">
                                LinkedIn <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text ">
                                    <i class="bi bi-linkedin"></i>
                                </span>
                                <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror"
                                    id="linkedin_url" name="linkedin_url"
                                    value="{{ old('linkedin_url', $portfolio->linkedin_url) }}"
                                    placeholder="https://linkedin.com/in/username" maxlength="255">
                                @error('linkedin_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="twitter_url" class="form-label fw-semibold small">
                                Twitter / X <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text ">
                                    <i class="bi bi-twitter"></i>
                                </span>
                                <input type="url" class="form-control @error('twitter_url') is-invalid @enderror"
                                    id="twitter_url" name="twitter_url"
                                    value="{{ old('twitter_url', $portfolio->twitter_url) }}"
                                    placeholder="https://twitter.com/username" maxlength="255">
                                @error('twitter_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Skills Section --}}
                        <div class="col-12 mt-2">
                            <h6 class="text-uppercase text-muted fw-semibold small border-bottom pb-2 mb-1">
                                Skills
                            </h6>
                        </div>

                        <div class="col-12">
                            <label for="skills" class="form-label fw-semibold small">
                                Skills <span class="text-muted fw-normal">(search or type to add new)</span>
                            </label>
                            <div class="form-text">Search from list or type a custom skill and press <kbd>Enter</kbd>.</div>
                            <select id="skills" name="skills[]" class="form-select" multiple></select>
                        </div>





                        {{-- Bio --}}
                        <div class="col-12 mt-2">
                            <h6 class="text-uppercase text-muted fw-semibold small border-bottom pb-2 mb-1">
                                Bio
                            </h6>
                        </div>

                        <div class="col-12">
                            <label for="bio" class="form-label fw-semibold small">
                                Short Bio <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4"
                                placeholder="Write a few lines about yourself…">{{ old('bio', $portfolio->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="d-flex justify-content-start gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-light  px-4">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>

                </form>


            </div>
        </div>


    </div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {

    const skills = [
        // ── Languages
        { id: 'PHP',             text: 'PHP',             category: 'Languages' },
        { id: 'JavaScript',      text: 'JavaScript',      category: 'Languages' },
        { id: 'Python',          text: 'Python',          category: 'Languages' },
        { id: 'Java',            text: 'Java',            category: 'Languages' },

        // ── Frontend
        { id: 'Vue.js',          text: 'Vue.js',          category: 'Frontend' },
        { id: 'Tailwind CSS',    text: 'Tailwind CSS',    category: 'Frontend' },
        { id: 'Bootstrap',       text: 'Bootstrap',       category: 'Frontend' },


        // ── Backend
        { id: 'Laravel',         text: 'Laravel',         category: 'Backend' },

        { id: 'CodeIgniter',     text: 'CodeIgniter',     category: 'Backend' },

        // ── Database
        { id: 'MySQL',           text: 'MySQL',           category: 'Database' },
        { id: 'PostgreSQL',      text: 'PostgreSQL',      category: 'Database' },

        // ── DevOps

        { id: 'Nginx',           text: 'Nginx',           category: 'DevOps' },

        // ── Tools
        { id: 'Git',             text: 'Git',             category: 'Tools' },

    ];

    // Group into optgroups by category
    const grouped = Object.entries(
        skills.reduce((acc, s) => {
            (acc[s.category] = acc[s.category] || []).push({ id: s.id, text: s.text });
            return acc;
        }, {})
    ).map(([label, children]) => ({ text: label, children }));

    $('#skills').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search or add a skill…',
        allowClear: true,
        tags: true,
        data: grouped,
        tokenSeparators: [','],
        createTag: function (params) {
            const term = $.trim(params.term);
            if (!term) return null;
            return { id: term, text: term, newTag: true };
        },
        templateResult: function (data) {
            if (data.newTag) {
                return $('<span><i class="bi bi-plus-circle me-1 text-primary"></i>'
                    + data.text
                    + ' <small class="text-muted">(new)</small></span>');
            }
            return data.text;
        },
    });

});
</script>
@endpush
