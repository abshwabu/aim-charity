@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? ($content['intro'] ?? null);
    $labels = is_array($content['labels'] ?? null) ? $content['labels'] : [];
    $buttonLabel = $content['button_label'] ?? null;
    $privacyNote = $content['privacy_note'] ?? null;
    $memberGroups = $allItems['member_groups'] ?? \App\Models\MemberGroup::query()->visible()->ordered()->get();
@endphp

<x-section-wrapper
    :section="$section"
    anchor="volunteer"
    class="py-16 md:py-24"
    :show-circle-motif="true"
>
    <div class="max-w-4xl mx-auto flex flex-col gap-10">
        {{-- Section Heading --}}
        <div class="text-center">
            <x-heading-block
                :eyebrow="$eyebrow"
                :heading="$heading"
                :subheading="$subheading"
                align="center"
                size="lg"
            />
        </div>

        {{-- Application Form with Progressive Enhancement --}}
        <div
            class="rounded-theme bg-surface border border-border p-6 sm:p-10 shadow-sm"
            x-data="{
                submitting: false,
                submitted: false,
                successMessage: '{{ addslashes($content['success_message'] ?? '') }}',
                errorMessage: '',
                submitForm(e) {
                    this.submitting = true;
                    this.errorMessage = '';
                    fetch('{{ route('volunteer.store') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(e.target)
                    })
                    .then(res => res.json().then(data => ({ ok: res.ok, status: res.status, data })))
                    .then(result => {
                        this.submitting = false;
                        if (result.ok && result.data.success) {
                            this.submitted = true;
                            if (result.data.message) {
                                this.successMessage = result.data.message;
                            }
                            e.target.reset();
                        } else {
                            let errs = result.data.errors ? Object.values(result.data.errors).flat() : [];
                            this.errorMessage = errs[0] || result.data.message || '';
                        }
                    })
                    .catch(() => {
                        this.submitting = false;
                        this.errorMessage = '';
                    });
                }
            }"
        >
            {{-- Non-JS Flash Success Message --}}
            @if(session('success'))
                <div class="p-6 rounded-theme bg-primary/10 border border-primary/20 text-primary text-center flex flex-col items-center gap-3 mb-6">
                    <x-icon name="heroicon-o-check-circle" class="w-8 h-8 text-accent" />
                    <p class="text-base font-medium text-text">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Alpine Inline Success Message --}}
            <template x-if="submitted">
                <div class="p-6 rounded-theme bg-primary/10 border border-primary/20 text-primary text-center flex flex-col items-center gap-3">
                    <x-icon name="heroicon-o-check-circle" class="w-8 h-8 text-accent" />
                    <p class="text-base font-medium text-text" x-text="successMessage"></p>
                </div>
            </template>

            {{-- Alpine Inline Error Message --}}
            <template x-if="errorMessage">
                <div class="p-4 rounded-theme bg-red-500/10 border border-red-500/20 text-red-700 text-sm mb-6">
                    <p x-text="errorMessage"></p>
                </div>
            </template>

            <form
                x-show="!submitted"
                action="{{ route('volunteer.store') }}"
                method="POST"
                class="flex flex-col gap-6"
                @submit.prevent="submitForm($event)"
            >
                @csrf

                {{-- Spam Protection: Honeypot & Time Token --}}
                <input type="text" name="_hp_website" value="" class="hidden sr-only" tabindex="-1" autocomplete="off" aria-hidden="true" />
                <input type="hidden" name="_form_time" value="{{ \App\Support\SpamProtection::generateToken() }}" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Name --}}
                    @if(filled($labels['name'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-name" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['name'] }}
                            </label>
                            <input
                                type="text"
                                id="vol-name"
                                name="name"
                                required
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif

                    {{-- Email --}}
                    @if(filled($labels['email'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-email" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['email'] }}
                            </label>
                            <input
                                type="email"
                                id="vol-email"
                                name="email"
                                required
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Phone --}}
                    @if(filled($labels['phone'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-phone" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['phone'] }}
                            </label>
                            <input
                                type="tel"
                                id="vol-phone"
                                name="phone"
                                required
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif

                    {{-- Preferred Member Group Select --}}
                    @if($memberGroups->count() > 0)
                        <div class="flex flex-col gap-2">
                            <label for="vol-group" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['group_label'] ?? ($labels['member_group'] ?? ($labels['organization'] ?? '')) }}
                            </label>
                            <select
                                id="vol-group"
                                name="member_group_id"
                                aria-label="{{ $labels['group_label'] ?? ($labels['member_group'] ?? 'Preferred Member Group') }}"
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            >
                                <option value="">{{ $labels['all_groups'] ?? '' }}</option>
                                @foreach($memberGroups as $mg)
                                    <option value="{{ $mg->id }}">{{ $mg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Skills --}}
                    @if(filled($labels['skills'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-skills" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['skills'] }}
                            </label>
                            <input
                                type="text"
                                id="vol-skills"
                                name="skills"
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif

                    {{-- Availability --}}
                    @if(filled($labels['availability'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-avail" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['availability'] }}
                            </label>
                            <input
                                type="text"
                                id="vol-avail"
                                name="availability"
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif
                </div>

                {{-- Message --}}
                @if(filled($labels['message'] ?? null))
                    <div class="flex flex-col gap-2">
                        <label for="vol-message" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                            {{ $labels['message'] }}
                        </label>
                        <textarea
                            id="vol-message"
                            name="message"
                            rows="4"
                            class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                        ></textarea>
                    </div>
                @endif

                {{-- Privacy Note --}}
                @if(filled($privacyNote))
                    <p class="text-xs text-text-muted leading-relaxed font-normal">
                        {{ $privacyNote }}
                    </p>
                @endif

                {{-- Submit Button --}}
                @if(filled($buttonLabel))
                    <div class="pt-2">
                        <x-button
                            type="submit"
                            variant="primary"
                            size="lg"
                            class="w-full sm:w-auto"
                            ::disabled="submitting"
                        >
                            {{ $buttonLabel }}
                        </x-button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-section-wrapper>
