{{-- Shared RSVP form section (shown on active events with output_type = rsvp) --}}
<section id="rsvp" class="border-t border-ink-100 bg-ink-50/70">
    <div class="mx-auto max-w-3xl px-4 py-16">
        <div class="text-center">
            <p class="text-xs font-bold uppercase tracking-[0.3em]" style="color: {{ $event->brand_color }};">RSVP</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-ink-950">Will you be joining us?</h2>
            <p class="mt-3 text-ink-600">Let the organiser know — it only takes a moment.</p>
        </div>

        @if ($errors->any())
            <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-5">
                <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('events.rsvp.store', ['slug' => $event->slug]) }}"
              class="mt-8 rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="rsvp-name" class="block text-sm font-semibold text-ink-700">Full name <span class="text-red-500">*</span></label>
                    <input id="rsvp-name" name="name" type="text" required maxlength="120" value="{{ old('name') }}"
                           class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:border-ink-400 focus:ring-4 focus:ring-ink-200">
                </div>
                <div>
                    <label for="rsvp-email" class="block text-sm font-semibold text-ink-700">Email <span class="text-red-500">*</span></label>
                    <input id="rsvp-email" name="email" type="email" required maxlength="180" value="{{ old('email') }}"
                           class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:ring-4">
                </div>
                <div>
                    <label for="rsvp-phone" class="block text-sm font-semibold text-ink-700">Phone (optional)</label>
                    <input id="rsvp-phone" name="phone" type="tel" maxlength="40" value="{{ old('phone') }}"
                           class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:ring-4">
                </div>
                <div>
                    <label for="rsvp-guests" class="block text-sm font-semibold text-ink-700">Number of guests <span class="text-red-500">*</span></label>
                    <select id="rsvp-guests" name="guests" required
                            class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-[15px] outline-none transition focus:ring-4">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" @selected((int) old('guests', 1) === $i)>{{ $i }}{{ $i > 1 ? ' guests' : ' guest' }}</option>
                        @endfor
                        <option value="11" @selected((int) old('guests') > 10)>More than 10 (note below)</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="rsvp-message" class="block text-sm font-semibold text-ink-700">Message (optional)</label>
                    <textarea id="rsvp-message" name="message" rows="4" maxlength="2000"
                              class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:ring-4">{{ old('message') }}</textarea>
                </div>
            </div>

            <button type="submit"
                    class="mt-8 w-full rounded-full px-8 py-4 text-base font-bold text-white shadow-lg transition hover:-translate-y-0.5"
                    style="background: {{ $event->brand_color }};">
                Send my RSVP
            </button>
        </form>
    </div>
</section>
