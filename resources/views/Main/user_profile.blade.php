@extends('main.layouts.main')

@section('content')
<div class="container py-5">
  <!-- Flash Messages -->
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row">
    <!-- Profile Sidebar -->
    <div class="col-lg-3">
      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body text-center p-4">
          @if(auth()->user()->profile && auth()->user()->profile->avatar)
            <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="{{ auth()->user()->name }}"
                 class="rounded-circle img-fluid mb-3" style="width: 120px; height: 120px; object-fit: cover;">
          @else
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                 style="width: 120px; height: 120px; font-size: 3rem;">
              {{ substr(auth()->user()->name, 0, 1) }}
            </div>
          @endif
          <h5 class="mb-1">{{ auth()->user()->name }}</h5>
          <p class="text-muted small">{{ auth()->user()->email }}</p>
          <div class="d-grid gap-2 mt-3">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
              <i class="bi bi-pencil-square me-2"></i>Edit Profile
            </button>
          </div>
        </div>
      </div>

      <!-- Navigation Menu -->
      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
          <nav class="nav flex-column">
            <a class="nav-link py-3 px-4 active border-bottom" href="#account" data-bs-toggle="pill" role="tab">
              <i class="bi bi-person me-2"></i>Account Details
            </a>
            <a class="nav-link py-3 px-4 border-bottom" href="#orders" data-bs-toggle="pill" role="tab">
              <i class="bi bi-bag me-2"></i>Orders
              <span class="badge bg-primary rounded-pill float-end">{{ auth()->user()->orders->count() }}</span>
            </a>
            <a class="nav-link py-3 px-4 border-bottom" href="#wishlist" data-bs-toggle="pill" role="tab">
              <i class="bi bi-heart me-2"></i>Wishlist
              <span class="badge bg-primary rounded-pill float-end">{{ $wishlistCount }}</span>
            </a>
            <a class="nav-link py-3 px-4 border-bottom" href="#addresses" data-bs-toggle="pill" role="tab">
              <i class="bi bi-geo-alt me-2"></i>Addresses
            </a>
            <a class="nav-link py-3 px-4" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </nav>
        </div>
      </div>
    </div>

    <!-- Profile Content -->
    <div class="col-lg-9">
      <div class="tab-content">
        <!-- Account Details Tab -->
        <div class="tab-pane fade show active" id="account" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-0">
              <h5 class="mb-0">Account Details</h5>
            </div>
            <div class="card-body p-4">
              <div class="row mb-3">
                <div class="col-sm-3">
                  <p class="text-muted mb-0">Full Name</p>
                </div>
                <div class="col-sm-9">
                  <p class="mb-0">{{ auth()->user()->name }}</p>
                </div>
              </div>
              <hr>
              <div class="row mb-3">
                <div class="col-sm-3">
                  <p class="text-muted mb-0">Email</p>
                </div>
                <div class="col-sm-9">
                  <p class="mb-0">{{ auth()->user()->email }}</p>
                </div>
              </div>
              <hr>
              <div class="row mb-3">
                <div class="col-sm-3">
                  <p class="text-muted mb-0">Phone</p>
                </div>
                <div class="col-sm-9">
                  <p class="mb-0">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                </div>
              </div>

              @if(auth()->user()->profile)
              <hr>
              <div class="row mb-3">
                <div class="col-sm-3">
                  <p class="text-muted mb-0">Gender</p>
                </div>
                <div class="col-sm-9">
                  <p class="mb-0">{{ auth()->user()->profile->gender ? ucfirst(auth()->user()->profile->gender) : 'Not provided' }}</p>
                </div>
              </div>
              <hr>
              <div class="row mb-3">
                <div class="col-sm-3">
                  <p class="text-muted mb-0">Birth Date</p>
                </div>
                <div class="col-sm-9">
                  <p class="mb-0">{{ auth()->user()->profile->birth_date ? date('F j, Y', strtotime(auth()->user()->profile->birth_date)) : 'Not provided' }}</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <p class="text-muted mb-0">Bio</p>
                </div>
                <div class="col-sm-9">
                  <p class="mb-0">{{ auth()->user()->profile->bio ?? 'No bio provided.' }}</p>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Orders Tab -->
        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
            <h3 class="mb-4">Order History</h3>

            @if($user->mainOrders->isEmpty())
                <div class="alert alert-info">
                    You haven't placed any orders yet.
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('main-products.index') }}" class="btn btn-primary">Start Shopping</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->mainOrders->take(5) as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{!!
                                        $order->status === 'completed' ? 'success' :
                                        ($order->status === 'cancelled' ? 'danger' :
                                        ($order->status === 'processing' ? 'info' : 'warning'))
                                    !!}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('main-orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('main-orders.index') }}" class="btn btn-outline-primary">View All Orders</a>
                </div>
            @endif
        </div>

        <!-- Wishlist Tab -->
        <div class="tab-pane fade" id="wishlist" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
              <h5 class="mb-0">My Wishlist</h5>
              @if(isset($offcanvasWishlistItems) && $offcanvasWishlistItems->count() > 0)
              <form action="{{ route('wishlists.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Clear All</button>
              </form>
              @endif
            </div>
            <div class="card-body p-0">
              @if(isset($offcanvasWishlistItems) && $offcanvasWishlistItems->count() > 0)
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($offcanvasWishlistItems as $item)
                      <tr class="wishlist-row" data-item-id="{{ $item->id }}">
                        <td>
                          <div class="d-flex align-items-center">
                            @if($item->product->images->first())
                              <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                                   alt="{{ $item->product->name }}" width="50" class="img-thumbnail me-3">
                            @endif
                            <div>
                              <h6 class="mb-0">{{ $item->product->name }}</h6>
                              <small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                            </div>
                          </div>
                        </td>
                        <td>${{ number_format($item->product->price, 2) }}</td>
                        <td>
                          <div class="btn-group btn-group-sm">
                            <form action="{{ route('carts.store') }}" method="POST" class="add-to-cart-form">
                              @csrf
                              <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                              <input type="hidden" name="quantity" value="1">
                              <input type="hidden" name="wishlist_item_id" value="{{ $item->id }}">
                              <button type="submit" class="btn btn-sm btn-primary me-2">Add to Cart</button>
                            </form>

                            <form action="{{ route('wishlists.items.destroy', $item->id) }}" method="POST" class="wishlist-remove-form">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="text-center py-5">
                  <div class="mb-3">
                    <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
                  </div>
                  <h6 class="mb-3">Your wishlist is empty</h6>
                  <a href="{{ route('main-products.index') }}" class="btn btn-primary">Browse Products</a>
                </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Addresses Tab -->
        <div class="tab-pane fade" id="addresses" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
              <h5 class="mb-0">My Addresses</h5>
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                Add New Address
              </button>
            </div>
            <div class="card-body p-4">
              @if(auth()->user()->addresses && auth()->user()->addresses->count() > 0)
                <div class="row">
                  @foreach(auth()->user()->addresses as $address)
                    <div class="col-md-6 mb-4">
                      <div class="card h-100">
                        <div class="card-body">
                          <address class="mb-0">
                            <strong>{{ $address->street }}</strong><br>
                            {{ $address->city }}<br>
                            {{ $address->country }} {{ $address->zip_code }}<br>
                            @if($address->phone)
                              <abbr title="Phone">P:</abbr> {{ $address->phone }}
                            @endif
                          </address>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-end gap-2">
                          <button type="button" class="btn btn-sm btn-outline-secondary edit-address-btn"
                                  data-id="{{ $address->id }}"
                                  data-country="{{ $address->country }}"
                                  data-city="{{ $address->city }}"
                                  data-street="{{ $address->street }}"
                                  data-zip="{{ $address->zip_code }}"
                                  data-phone="{{ $address->phone }}">
                            Edit
                          </button>
                          <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this address?')">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="text-center py-3">
                  <p>No addresses saved yet. Add a new address to get started.</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('main.user_profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}">
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}">
          </div>
          <div class="mb-3">
            <label for="avatar" class="form-label">Profile Picture</label>
            <input type="file" class="form-control" id="avatar" name="avatar">

            <!-- Add this preview section -->
            <div class="mt-2">
              @if(auth()->user()->profile && auth()->user()->profile->avatar)
                <div class="d-flex align-items-center">
                  <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}"
                       alt="Current avatar" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                  <small class="text-muted">Current profile picture</small>
                </div>
              @endif
            </div>
          </div>
          <div class="mb-3">
            <label for="birth_date" class="form-label">Birth Date</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date"
                   value="{{ auth()->user()->profile ? auth()->user()->profile->birth_date : '' }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Gender</label>
            <div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male"
                       {{ auth()->user()->profile && auth()->user()->profile->gender == 'male' ? 'checked' : '' }}>
                <label class="form-check-label" for="genderMale">Male</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female"
                       {{ auth()->user()->profile && auth()->user()->profile->gender == 'female' ? 'checked' : '' }}>
                <label class="form-check-label" for="genderFemale">Female</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="genderOther" value="other"
                       {{ auth()->user()->profile && auth()->user()->profile->gender == 'other' ? 'checked' : '' }}>
                <label class="form-check-label" for="genderOther">Other</label>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control" id="bio" name="bio" rows="3">{{ auth()->user()->profile ? auth()->user()->profile->bio : '' }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAddressModalLabel">Add New Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('addresses.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <!-- Country field -->
          <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" class="form-control" id="country" name="country" required>
          </div>

          <!-- City field -->
          <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" required>
          </div>

          <!-- Street field -->
          <div class="mb-3">
            <label for="street" class="form-label">Street</label>
            <input type="text" class="form-control" id="street" name="street" required>
          </div>

          <!-- ZIP Code field -->
          <div class="mb-3">
            <label for="zip_code" class="form-label">ZIP Code</label>
            <input type="text" class="form-control" id="zip_code" name="zip_code">
            <div class="form-text">Optional</div>
          </div>

          <!-- Phone field -->
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone">
            <div class="form-text">Optional</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Address</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAddressModalLabel">Edit Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="edit-address-form" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <!-- Country field -->
          <div class="mb-3">
            <label for="edit_country" class="form-label">Country</label>
            <input type="text" class="form-control" id="edit_country" name="country" required>
          </div>

          <!-- City field -->
          <div class="mb-3">
            <label for="edit_city" class="form-label">City</label>
            <input type="text" class="form-control" id="edit_city" name="city" required>
          </div>

          <!-- Street field -->
          <div class="mb-3">
            <label for="edit_street" class="form-label">Street</label>
            <input type="text" class="form-control" id="edit_street" name="street" required>
          </div>

          <!-- ZIP Code field -->
          <div class="mb-3">
            <label for="edit_zip_code" class="form-label">ZIP Code</label>
            <input type="text" class="form-control" id="edit_zip_code" name="zip_code">
          </div>

          <!-- Phone field -->
          <div class="mb-3">
            <label for="edit_phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="edit_phone" name="phone">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Address</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Add to your existing DOMContentLoaded script
  document.addEventListener('DOMContentLoaded', function() {
    // Edit address button handling
    const editButtons = document.querySelectorAll('.edit-address-btn');

    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        const addressId = this.dataset.id;
        const country = this.dataset.country;
        const city = this.dataset.city;
        const street = this.dataset.street;
        const zipCode = this.dataset.zip;
        const phone = this.dataset.phone;

        // Create a modal for editing
        const modal = new bootstrap.Modal(document.getElementById('editAddressModal'));

        // Set form action
        document.getElementById('edit-address-form').action = `/addresses/${addressId}`;

        // Set form values
        document.getElementById('edit_country').value = country;
        document.getElementById('edit_city').value = city;
        document.getElementById('edit_street').value = street;
        document.getElementById('edit_zip_code').value = zipCode;
        document.getElementById('edit_phone').value = phone;

        modal.show();
      });
    });
  });
</script>
@endpush
