document.addEventListener('alpine:init', () => {
    Alpine.store('ratings', []);

    Alpine.data('products', () => ({
        items: [],
        ratings: [],
        async fetchProducts() {
            try {
                const response = await fetch('php/getFilm.php');
                const data = await response.json();
                this.items = data;
                this.ratings = this.items.map(item => item.rating);
                Alpine.store('ratings', this.ratings);
            } catch (error) {
                console.error("Gagal mengambil data film:", error);
            }
    },
    generateStars(rating) {
        let stars = "";
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += `<svg width="24" height="24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>`;
            } else {
                stars += `<svg width="24" height="24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>`;
            }
        }
        return stars;
    }}));

    Alpine.store('cart', {
        items: [],
        total: 0,
        quantity: 0,
        add(newItem) {
        if ( this.items.length < 1 ) {
            this.items.push({...newItem, quantity: 1, total: Number(newItem.harga_sewa)});
        } else {
            if (this.items[0].film_id === newItem.film_id) {
                this.items[0].quantity++;
                this.items[0].total = Number(newItem.harga_sewa) * this.items[0].quantity;
            } else {
                this.items = [{...newItem, quantity: 1, total: Number(newItem.harga_sewa)}];
            }
        }
        this.total = this.items[0].total;
        this.quantity = this.items[0].quantity;
                },
        remove(film_id) {
    if (this.items.length > 0 && this.items[0].film_id === film_id) {
        if (this.items[0].quantity > 1) {
            // Kurangi quantity jika lebih dari 1
            this.items[0].quantity--;
            this.items[0].total -= Number(this.items[0].harga_sewa);
        } else {
            // Hapus item jika quantity tinggal 1
            this.items = [];
        }
    }
    this.total = this.items.length > 0 ? this.items[0].total : 0;
            this.quantity = this.items.length > 0 ? this.items[0].quantity : 0;
        }
                });
                Alpine.store('modal', {
                    items: [],
                    total: 0,
                    quantity: 0,
                    deskripsi: '',
                    rating: 0, 
                    generateStars() {
                        let stars = "";
                        const numRating = Number(this.rating); 
                        
                        for (let i = 1; i <= 5; i++) {
                            stars += `<svg width="24" height="24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                      ${i <= numRating ? 'fill="currentColor"' : 'fill="none"'}>
                                <use href="img/feather-sprite.svg#star" />
                            </svg>`;
                        }
                        return stars;
                    },
                
                    add(newItem) {
                        this.items = [{ 
                            ...newItem, 
                            quantity: 1, 
                            total: Number(newItem.harga_sewa),
                            deskripsi: newItem.deskripsi,
                            rating: Number(newItem.rating) 
                        }];
                        this.rating = this.items[0].rating;
                        this.total = this.items[0].total;
                        this.quantity = this.items[0].quantity;
                        this.deskripsi = this.items[0].deskripsi;
                    },
                
                    remove(film_id) {
                        if (this.items.length > 0 && this.items[0].film_id === film_id) {
                            if (this.items[0].quantity > 1) {
                                this.items[0].quantity--;
                                this.items[0].total -= Number(this.items[0].harga_sewa);
                            } else {
                                this.items = [];
                            }
                        }
                
                        this.total = this.items.length > 0 ? this.items[0].total : 0;
                        this.quantity = this.items.length > 0 ? this.items[0].quantity : 0;
                        this.rating = this.items.length > 0 ? this.items[0].rating : 0;
                    }
                });                
                });


// form validation
const checkoutButton = document.querySelector('.checkout');
checkoutButton.disabled = true;

const form = document.querySelector('#checkoutForm');

form.addEventListener('keyup', function () {
    for (let i = 0; i < form.elements.length; i++) {
        if (form.elements[i].value.length !== 0) {
            checkoutButton.classList.remove('disabled');
            checkoutButton.classList.add('disabled');
        } else {
            return false;
        }
    }
    checkoutButton.disabled = false;
    checkoutButton.classList.remove('disabled');
});

// kirim data ketika tombol checkout di klik
checkoutButton.addEventListener('click', async function (e){
    e.preventDefault();
    const formData = new FormData(form);
    const data = new URLSearchParams(formData);
    const objData = Object.fromEntries(data);

    const itemsFormatted = Alpine.store('cart').items.map(item => ({
        id: item.film_id,            
        name: item.judul_film,        
        price: Number(item.harga_sewa), 
        quantity: item.quantity
    }));

    objData.items = JSON.stringify(itemsFormatted); 
    objData.total = Alpine.store('cart').total; 

    try {
        const response = await fetch('php/placeOrder.php', {
            method: 'POST',
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(objData),
        });
        const token = await response.text();
        window.snap.pay(token);
    } catch (err) {
        console.log(err.message);
    }

});

// konversi ke rupiah
const rupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        }).format(number);
};