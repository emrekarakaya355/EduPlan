<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    {{-- Filters --}}
                    <div class="row mb-3">


                    {{-- Results Info --}}
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Toplam {{ $constraints->total() }} kayıt bulundu
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <select wire:model="perPage" class="form-control d-inline-block w-auto">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-muted ml-2">kayıt göster</span>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <button type="button"
                                            class="btn btn-link p-0 text-dark font-weight-bold"
                                            wire:click="sortBy('instructor_id')">
                                        Instructor
                                        @if($sortBy === 'instructor_id')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>
                                    <button type="button"
                                            class="btn btn-link p-0 text-dark font-weight-bold"
                                            wire:click="sortBy('day_of_week')">
                                        Gün
                                        @if($sortBy === 'day_of_week')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>
                                    <button type="button"
                                            class="btn btn-link p-0 text-dark font-weight-bold"
                                            wire:click="sortBy('start_time')">
                                        Başlangıç
                                        @if($sortBy === 'start_time')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>
                                    <button type="button"
                                            class="btn btn-link p-0 text-dark font-weight-bold"
                                            wire:click="sortBy('end_time')">
                                        Bitiş
                                        @if($sortBy === 'end_time')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>Not</th>
                                <th>
                                    <button type="button"
                                            class="btn btn-link p-0 text-dark font-weight-bold"
                                            wire:click="sortBy('created_by')">
                                        Oluşturan
                                        @if($sortBy === 'created_by')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>
                                    <button type="button"
                                            class="btn btn-link p-0 text-dark font-weight-bold"
                                            wire:click="sortBy('created_at')">
                                        Oluşturma Tarihi
                                        @if($sortBy === 'created_at')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>Güncelleyen</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($constraints as $constraint)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $constraint->instructor->name ?? 'N/A' }}</strong>
                                            @if($constraint->instructor)
                                                <br>
                                                <small class="text-muted">{{ $constraint->instructor->email }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                            <span class="badge badge-primary">
                                                {{ $this->getDayName($constraint->day_of_week) }}
                                            </span>
                                    </td>
                                    <td>
                                            <span class="badge badge-success">
                                                {{ \Carbon\Carbon::parse($constraint->start_time)->format('H:i') }}
                                            </span>
                                    </td>
                                    <td>
                                            <span class="badge badge-danger">
                                                {{ \Carbon\Carbon::parse($constraint->end_time)->format('H:i') }}
                                            </span>
                                    </td>
                                    <td>
                                        @if($constraint->note)
                                            <span class="text-muted">{{ Str::limit($constraint->note, 50) }}</span>
                                            @if(strlen($constraint->note) > 50)
                                                <br>
                                                <small>
                                                    <a href="#"
                                                       data-toggle="tooltip"
                                                       title="{{ $constraint->note }}">
                                                        Devamını gör...
                                                    </a>
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($constraint->createdBy)
                                            <div>
                                                <strong>{{ $constraint->createdBy->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $constraint->createdBy->email }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">Bilinmiyor</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            {{ $constraint->created_at->format('d.m.Y') }}
                                            <br>
                                            <small class="text-muted">{{ $constraint->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($constraint->updatedBy)
                                            <div>
                                                <strong>{{ $constraint->updatedBy->name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $constraint->updated_at->format('d.m.Y H:i') }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <div class="py-4">
                                            <i class="fas fa-search fa-2x mb-3"></i>
                                            <h5>Kayıt bulunamadı</h5>
                                            <p>Arama kriterlerinizi değiştirmeyi deneyin.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            {{ $constraints->firstItem() }}-{{ $constraints->lastItem() }}
                            / {{ $constraints->total() }} kayıt gösteriliyor
                        </div>
                        <div>
                            {{ $constraints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Tooltip'leri aktif et
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
