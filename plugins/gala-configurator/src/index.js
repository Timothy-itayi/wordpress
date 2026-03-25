import { useState, useEffect, render } from '@wordpress/element';

const GalaConfigurator = () => {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    
    const [build, setBuild] = useState({
        cpu: '',
        gpu: '',
        ram: ''
    });

    useEffect(() => {
        const fetchUrl = `${galaConfig.apiUrl}products?status=publish&per_page=50`;

        fetch(fetchUrl)
            .then(response => response.json())
            .then(data => {
                setProducts(data);
                setLoading(false);
            })
            .catch(error => {
                console.error('Error fetching WooCommerce products:', error);
                setLoading(false);
            });
    }, []);

    const handleSelectChange = (partType, value) => {
        setBuild(prevBuild => ({
            ...prevBuild,
            [partType]: value
        }));
    };

    const handleAddToCart = async () => {
        if (!build.cpu || !build.gpu) {
            alert('Please select your CPU and GPU before adding to cart!');
            return;
        }

        const btn = document.getElementById('gala-cart-btn');
        if(btn) btn.innerText = 'Building Rig...';

        const cartEndpoint = '/wp-json/wc/store/v1/cart/add-item';
        
        // IMPORTANT: Replace '99' with your actual WooCommerce Product or Variation ID later
        const cartData = {
            id: 99, 
            quantity: 1,
        };

        try {
            const response = await fetch(cartEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': galaConfig.nonce 
                },
                body: JSON.stringify(cartData)
            });

            if (response.ok) {
                window.location.href = '/cart'; 
            } else {
                const errorData = await response.json();
                console.error('WooCommerce Error:', errorData);
                alert('There was an issue adding this to your cart.');
                if(btn) btn.innerText = 'Add to Cart';
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('Connection error.');
            if(btn) btn.innerText = 'Add to Cart';
        }
    };

    if (loading) {
        return <div className="gala-loader" style={{color: '#fff'}}>Loading Gala Power Configurator...</div>;
    }

    return (
        <div className="gala-configurator-ui" style={{ padding: '20px', background: '#111', color: '#fff', borderRadius: '8px' }}>
            <h2 style={{ color: '#00ffcc', marginTop: '0' }}>Configure Your Rig</h2>
            
            <div className="config-group" style={{ marginBottom: '15px' }}>
                <label style={{ display: 'block', marginBottom: '5px' }}>Processor (CPU)</label>
                <select 
                    value={build.cpu} 
                    onChange={(e) => handleSelectChange('cpu', e.target.value)}
                    style={{ width: '100%', padding: '10px' }}
                >
                    <option value="">Select a CPU...</option>
                    <option value="ryzen-5">AMD Ryzen 5 7600X</option>
                    <option value="intel-i7">Intel Core i7-14700K</option>
                </select>
            </div>

            <div className="config-group" style={{ marginBottom: '15px' }}>
                <label style={{ display: 'block', marginBottom: '5px' }}>Graphics Card (GPU)</label>
                <select 
                    value={build.gpu} 
                    onChange={(e) => handleSelectChange('gpu', e.target.value)}
                    style={{ width: '100%', padding: '10px' }}
                >
                    <option value="">Select a GPU...</option>
                    <option value="rtx-4060">NVIDIA RTX 4060</option>
                    <option value="rtx-4080">NVIDIA RTX 4080 Super</option>
                </select>
            </div>

            <div className="config-summary" style={{ marginTop: '20px', padding: '15px', border: '1px solid #333' }}>
                <h3 style={{ marginTop: '0' }}>Current Build:</h3>
                <ul>
                    <li>CPU: {build.cpu || 'None selected'}</li>
                    <li>GPU: {build.gpu || 'None selected'}</li>
                </ul>
                <button 
                    id="gala-cart-btn"
                    style={{ background: '#00ffcc', color: '#000', padding: '10px 20px', border: 'none', cursor: 'pointer', marginTop: '10px', fontWeight: 'bold' }}
                    onClick={handleAddToCart}
                >
                    Add to Cart
                </button>
            </div>
        </div>
    );
};

const rootElement = document.getElementById('gala-configurator-root');
if (rootElement) {
    render(<GalaConfigurator />, rootElement);
}