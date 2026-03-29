// assets/js/api.js

const API = {
    request: async (endpoint, method = 'GET', body = null) => {
        const options = { method, headers: {} };
        
        if (body instanceof FormData) {
            options.body = body;
        } else if (body) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(body);
        }
        
        try {
            const res = await fetch(`api/${endpoint}`, options);
            const data = await res.json();
            return { status: res.status, data };
        } catch (e) {
            console.error('API Error:', e);
            return { status: 500, data: { success: false, message: 'Network error or invalid JSON' } };
        }
    },
    
    auth: {
        me: () => API.request('auth.php?action=me'),
        login: (creds) => API.request('auth.php?action=login', 'POST', creds),
        register: (data) => API.request('auth.php?action=register', 'POST', data),
        logout: () => API.request('auth.php?action=logout', 'POST')
    },
    
    products: {
        list: (q='') => API.request(`products.php?action=list&q=${q}`),
        get: (id) => API.request(`products.php?action=get&id=${id}`),
        create: (data) => API.request('products.php?action=create', 'POST', data)
    },
    
    orders: {
        myOrders: () => API.request('orders.php?action=my_orders'),
        list: () => API.request('orders.php?action=list'),
        place: (items) => API.request('orders.php?action=place', 'POST', {items}),
        updateStatus: (data) => API.request('orders.php?action=update_status', 'POST', data)
    },
    
    wallet: {
        balance: () => API.request('wallet.php?action=balance'),
        history: () => API.request('wallet.php?action=history')
    },
    
    payment: {
        initInterswitch: (amount) => API.request('payment.php?action=init_interswitch', 'POST', {amount}),
        initInterswitchOrder: (order_id) => API.request('payment.php?action=init_interswitch_order', 'POST', {order_id}),
        verifyInterswitch: (txn_ref, amount, order_id = 0) => API.request('payment.php?action=verify_interswitch', 'POST', {txn_ref, amount, order_id}),
        payOrder: (order_id) => API.request('payment.php?action=pay_order', 'POST', {order_id})
    },
    
    containers: {
        list: () => API.request('containers.php?action=list'),
        create: (name) => API.request('containers.php?action=create', 'POST', {name}),
        updateStatus: (data) => API.request('containers.php?action=update_status', 'POST', data)
    },
    
    merchants: {
        list: () => API.request('merchants.php?action=list'),
        create: (data) => API.request('merchants.php?action=create', 'POST', data)
    },
    
    chat: {
        send: (receiver_id, content, order_id = 0) => API.request('chat.php?action=send', 'POST', {receiver_id, content, order_id}),
        getHistory: (other_id, order_id = 0) => API.request(`chat.php?action=get_history&other_id=${other_id}&order_id=${order_id}`),
        getThreads: () => API.request('chat.php?action=get_threads')
    },
    
    reviews: {
        add: (product_id, rating, comment) => API.request('products.php?action=add_review', 'POST', {product_id, rating, comment}),
        get: (product_id) => API.request(`products.php?action=get_reviews&product_id=${product_id}`)
    },
    
    wishlist: {
        toggle: (product_id) => API.request('products.php?action=toggle_wishlist', 'POST', {product_id}),
        list: () => API.request('products.php?action=get_wishlist')
    }
};
