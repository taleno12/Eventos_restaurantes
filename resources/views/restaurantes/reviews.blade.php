{{-- resources/views/restaurants/partials/reviews.blade.php --}}
{{-- Incluir en la vista del restaurante con: @include('restaurants.partials.reviews', ['restaurant' => $restaurant]) --}}

<section class="reviews-section mt-10">

    {{-- ── Resumen de calificación ── --}}
    <div class="reviews-summary">
        <h2 class="reviews-title">Reseñas</h2>

        @if($restaurant->reviews_count > 0)
            <div class="rating-overview">
                <span class="rating-big">{{ $restaurant->average_rating }}</span>
                <div class="rating-stars-display">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= round($restaurant->average_rating) ? 'star--filled' : 'star--empty' }}">★</span>
                    @endfor
                </div>
                <span class="rating-count">{{ $restaurant->reviews_count }} {{ Str::plural('reseña', $restaurant->reviews_count) }}</span>
            </div>
        @else
            <p class="no-reviews">Sé el primero en dejar una reseña.</p>
        @endif
    </div>

    {{-- ── Mensajes flash ── --}}
    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert--error">{{ session('error') }}</div>
    @endif

    {{-- ── Formulario nueva reseña ── --}}
    @auth
        @php
            $userReview = $restaurant->reviews->firstWhere('user_id', auth()->id());
        @endphp

        @unless($userReview)
        <div class="review-form-card">
            <h3 class="review-form-title">Deja tu reseña</h3>
            <form action="{{ route('reviews.store', $restaurant) }}" method="POST">
                @csrf

                {{-- Estrellas interactivas --}}
                <div class="star-picker" id="starPicker">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star-pick" data-value="{{ $i }}">★</span>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="">
                @error('rating')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <div class="form-group">
                    <input type="text"
                           name="title"
                           placeholder="Título (opcional)"
                           maxlength="100"
                           value="{{ old('title') }}"
                           class="form-input">
                    @error('title')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <textarea name="body"
                              placeholder="Cuéntanos tu experiencia... (opcional)"
                              maxlength="1000"
                              rows="4"
                              class="form-input">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Publicar reseña</button>
            </form>
        </div>
        @endunless
    @else
        <p class="login-prompt">
            <a href="{{ route('login') }}" class="link-orange">Inicia sesión</a> para dejar una reseña.
        </p>
    @endauth

    {{-- ── Lista de reseñas ── --}}
    <div class="reviews-list">
        @forelse($restaurant->reviews()->with('user')->latest()->get() as $review)
            <article class="review-card">
                <div class="review-header">
                    <div class="review-author-info">
                        <span class="review-author">{{ $review->user->name }}</span>
                        <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $review->rating ? 'star--filled' : 'star--empty' }}">★</span>
                        @endfor
                    </div>
                </div>

                @if($review->title)
                    <h4 class="review-body-title">{{ $review->title }}</h4>
                @endif

                @if($review->body)
                    <p class="review-body">{{ $review->body }}</p>
                @endif

                {{-- Acciones si es el autor --}}
                @auth
                    @if(auth()->id() === $review->user_id)
                        <div class="review-actions">
                            {{-- Botón editar (abre modal inline) --}}
                            <button class="btn-edit"
                                    onclick="toggleEditForm('edit-{{ $review->id }}')">
                                Editar
                            </button>

                            {{-- Eliminar --}}
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta reseña?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </div>

                        {{-- Formulario edición inline (oculto) --}}
                        <div id="edit-{{ $review->id }}" class="edit-form" style="display:none">
                            <form action="{{ route('reviews.update', $review) }}" method="POST">
                                @csrf @method('PUT')

                                <div class="star-picker" id="starPicker-{{ $review->id }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star-pick {{ $i <= $review->rating ? 'active' : '' }}"
                                              data-value="{{ $i }}">★</span>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput-{{ $review->id }}"
                                       value="{{ $review->rating }}">

                                <input type="text" name="title" value="{{ $review->title }}"
                                       placeholder="Título" class="form-input" maxlength="100">

                                <textarea name="body" rows="3" class="form-input"
                                          maxlength="1000">{{ $review->body }}</textarea>

                                <div class="edit-actions">
                                    <button type="submit" class="btn-submit">Guardar</button>
                                    <button type="button" class="btn-cancel"
                                            onclick="toggleEditForm('edit-{{ $review->id }}')">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endauth
            </article>
        @empty
            <p class="no-reviews-yet">Aún no hay reseñas para este restaurante.</p>
        @endforelse
    </div>

</section>

{{-- ── Estilos ── --}}
<style>
.reviews-section { color: #fff; max-width: 760px; }

.reviews-title { font-size: 1.6rem; font-weight: 700; margin-bottom: .5rem; }

/* Resumen */
.rating-overview { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
.rating-big { font-size: 3rem; font-weight: 800; color: #e85d04; line-height: 1; }
.rating-count { color: #aaa; font-size: .9rem; }

/* Estrellas genéricas */
.star--filled { color: #e85d04; }
.star--empty  { color: #555; }

/* Picker interactivo */
.star-picker { display: flex; gap: .25rem; margin-bottom: .75rem; cursor: pointer; }
.star-pick   { font-size: 2rem; color: #555; transition: color .15s; }
.star-pick.active, .star-pick:hover { color: #e85d04; }

/* Formulario */
.review-form-card { background: #1a1a1a; border: 1px solid #2e2e2e; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; }
.review-form-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; }
.form-group  { margin-bottom: .75rem; }
.form-input  { width: 100%; background: #111; border: 1px solid #333; color: #fff; border-radius: 8px; padding: .6rem .9rem; font-size: .95rem; resize: vertical; }
.form-input:focus { outline: none; border-color: #e85d04; }
.field-error { color: #ff6b6b; font-size: .82rem; margin-top: .25rem; }
.btn-submit  { background: #e85d04; color: #fff; border: none; border-radius: 8px; padding: .6rem 1.4rem; font-weight: 700; cursor: pointer; }
.btn-submit:hover { background: #c44d00; }

/* Alertas */
.alert { padding: .75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: .9rem; }
.alert--success { background: #1a3a1a; border: 1px solid #2d6a2d; color: #6fcf6f; }
.alert--error   { background: #3a1a1a; border: 1px solid #6a2d2d; color: #cf6f6f; }

/* Lista */
.reviews-list { display: flex; flex-direction: column; gap: 1rem; }
.review-card  { background: #1a1a1a; border: 1px solid #2e2e2e; border-radius: 12px; padding: 1.25rem; }
.review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .5rem; }
.review-author { font-weight: 700; font-size: .95rem; }
.review-date   { color: #888; font-size: .8rem; display: block; margin-top: .1rem; }
.review-body-title { font-weight: 600; margin: .4rem 0 .25rem; }
.review-body   { color: #ccc; font-size: .92rem; line-height: 1.5; margin: 0; }

/* Acciones */
.review-actions { display: flex; gap: .5rem; margin-top: .75rem; }
.btn-edit   { background: transparent; border: 1px solid #e85d04; color: #e85d04; border-radius: 6px; padding: .3rem .75rem; cursor: pointer; font-size: .82rem; }
.btn-delete { background: transparent; border: 1px solid #666; color: #999; border-radius: 6px; padding: .3rem .75rem; cursor: pointer; font-size: .82rem; }
.btn-cancel { background: transparent; border: 1px solid #555; color: #aaa; border-radius: 6px; padding: .3rem .75rem; cursor: pointer; font-size: .82rem; }
.edit-form  { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #2e2e2e; }
.edit-actions { display: flex; gap: .5rem; margin-top: .5rem; }

.login-prompt { color: #888; font-size: .9rem; margin-bottom: 1.5rem; }
.link-orange  { color: #e85d04; }
.no-reviews, .no-reviews-yet { color: #888; font-size: .9rem; }
</style>

{{-- ── Script estrellas interactivas ── --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar todos los star pickers de la página
    document.querySelectorAll('.star-picker').forEach(picker => {
        const pickerId = picker.id;
        const inputId  = pickerId.replace('starPicker', 'ratingInput');
        const input    = document.getElementById(inputId);
        const stars    = picker.querySelectorAll('.star-pick');

        stars.forEach(star => {
            star.addEventListener('mouseenter', () => {
                const val = parseInt(star.dataset.value);
                stars.forEach(s => {
                    s.classList.toggle('active', parseInt(s.dataset.value) <= val);
                });
            });

            star.addEventListener('mouseleave', () => {
                const current = input ? parseInt(input.value) : 0;
                stars.forEach(s => {
                    s.classList.toggle('active', parseInt(s.dataset.value) <= current);
                });
            });

            star.addEventListener('click', () => {
                const val = parseInt(star.dataset.value);
                if (input) input.value = val;
                stars.forEach(s => {
                    s.classList.toggle('active', parseInt(s.dataset.value) <= val);
                });
            });
        });
    });
});

function toggleEditForm(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>