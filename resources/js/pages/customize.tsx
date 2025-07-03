import React, {useCallback, useState} from 'react';
import {useForm, usePage} from '@inertiajs/react';
import {
  Page,
  Card,
  FormLayout,
  Button,
  Modal,
  Tabs,
  Text,
  Divider,
  TextField,
} from '@shopify/polaris';
import {CartProps} from '@/types';

export default function Customize() {
  // Tab state
  const [selectedTab, setSelectedTab] = useState<'cart'|'buttons'>('cart');
  const handleTabChange = (tabIndex: number) => {
    setSelectedTab(tabIndex === 0 ? 'cart' : 'buttons');
  };

  // Hover states
  const [isAddHover, setIsAddHover] = useState(false);
  const [isBuyHover, setIsBuyHover] = useState(false);

  // Success modal
  const [modalOpen, setModalOpen] = useState(false);

  // Initial data
  const {cartSettings, addExtensionLink} = usePage<CartProps>().props;

  const d = cartSettings.data;

  // Inertia form
  const form = useForm({
    // Cart
    cart_bg_color:         d.cart_bg_color,
    cart_text_color:       d.cart_text_color,
    cart_price_text_color: d.cart_price_text_color,
    cart_position_from_bottom: d.cart_position_from_bottom,
    // Add to Cart
    btn_bg_color:          d.btn_bg_color,
    btn_text_color:        d.btn_text_color,
    btn_onhover_bg_color:  d.btn_onhover_bg_color,
    btn_onhover_text_color:d.btn_onhover_text_color,
    // Buy Now (defaults)
    buy_bg_color:          d.buy_bg_color,
    buy_text_color:        d.buy_text_color,
    buy_onhover_bg_color:  d.buy_onhover_bg_color,
    buy_onhover_text_color:d.buy_onhover_text_color,
  });

  const handleSubmit = useCallback((e: React.FormEvent) => {
    e.preventDefault();
    form.post(route('home'), {
      preserveScroll: true,
      onSuccess: () => setModalOpen(true),
    });
  }, [form]);

  // Preview bar
  const previewStyles: React.CSSProperties = {
    position:       'fixed',
    bottom:         form.data.cart_position_from_bottom + 'px',
    left:           0,
    right:          0,
    backgroundColor:form.data.cart_bg_color,
    color:          form.data.cart_text_color,
    display:        'flex',
    alignItems:     'center',
    justifyContent: 'space-between',
    padding:        '1rem',
    boxShadow:      '0 1px 6px rgba(0,0,0,0.1)',
    zIndex:         100,
  };

  // Add to Cart
  const addBase: React.CSSProperties = {
    backgroundColor: form.data.btn_bg_color,
    color:           form.data.btn_text_color,
    border:          `1px solid ${form.data.btn_bg_color}`,
    padding:         '0.8rem 1.5rem',
    fontWeight:      600,
    cursor:          'pointer',
    transition:      'all 0.3s ease',
  };
  const addHover: React.CSSProperties = {
    backgroundColor: form.data.btn_onhover_bg_color,
    color:           form.data.btn_onhover_text_color,
  };

  // Buy Now
  const buyBase: React.CSSProperties = {
    backgroundColor: form.data.buy_bg_color,
    color:           form.data.buy_text_color,
    border:          `1px solid ${form.data.buy_bg_color}`,
    padding:         '0.8rem 1.5rem',
    fontWeight:      600,
    cursor:          'pointer',
    transition:      'all 0.3s ease',
  };
  const buyHover: React.CSSProperties = {
    backgroundColor: form.data.buy_onhover_bg_color,
    color:           form.data.buy_onhover_text_color,
  };

  const tabs = [
    {id: 'cart',    content: 'Cart Appearance'},
    {id: 'buttons', content: 'Buttons'},
  ];

  const handleAddExtensionClick = () => {
    window.open(addExtensionLink);
  }

  return (
    <Page title="Customize Settings"
      primaryAction={{
        content: 'Add QuickStick to product page',
        destructive: true, // Makes it red
        onAction: handleAddExtensionClick,
      }}>
      <Tabs
        tabs={tabs}
        selected={tabs.findIndex(t => t.id === selectedTab)}
        onSelect={handleTabChange}
      />

      <form onSubmit={handleSubmit}>
        {selectedTab === 'cart' && (
          <Card>
            <div style={{padding: '1rem'}}>
              <Text as='h3' variant="headingMd">Cart Appearance</Text>
              <FormLayout>
                <FormLayout.Group>
                  <div>
                    <label htmlFor="cart_bg_color">Cart background</label>
                    <input
                      id="cart_bg_color"
                      type="color"
                      value={form.data.cart_bg_color}
                      onChange={e => form.setData('cart_bg_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div>
                    <label htmlFor="cart_text_color">Cart text</label>
                    <input
                      id="cart_text_color"
                      type="color"
                      value={form.data.cart_text_color}
                      onChange={e => form.setData('cart_text_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div>
                    <label htmlFor="cart_price_text_color">Price text</label>
                    <input
                      id="cart_price_text_color"
                      type="color"
                      value={form.data.cart_price_text_color}
                      onChange={e => form.setData('cart_price_text_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div style={{ display:'none' }}>
                    <label htmlFor="car_position_from_bottom">Cart position from bottom</label>
                    <TextField
                        label="(in px)"
                        type="number"
                        value={String(form.data.cart_position_from_bottom)}
                        onChange={(value) => {
                            const num = Math.max(0, Number(value));
                            form.setData('cart_position_from_bottom', Number(num))
                        }}
                        autoComplete="off"
                    />
                  </div>
                </FormLayout.Group>
              </FormLayout>
            </div>
          </Card>
        )}

        {selectedTab === 'buttons' && (
          <Card>
            <div style={{padding: '1rem'}}>
              <Text as='h3' variant="headingMd">Add to Cart Styles</Text>
              <FormLayout>
                <FormLayout.Group>
                  <div>
                    <label htmlFor="btn_bg_color">Background</label>
                    <input
                      id="btn_bg_color"
                      type="color"
                      value={form.data.btn_bg_color}
                      onChange={e => form.setData('btn_bg_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div>
                    <label htmlFor="btn_text_color">Text</label>
                    <input
                      id="btn_text_color"
                      type="color"
                      value={form.data.btn_text_color}
                      onChange={e => form.setData('btn_text_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div>
                    <label htmlFor="btn_onhover_bg_color">Hover background</label>
                    <input
                      id="btn_onhover_bg_color"
                      type="color"
                      value={form.data.btn_onhover_bg_color}
                      onChange={e => form.setData('btn_onhover_bg_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div>
                    <label htmlFor="btn_onhover_text_color">Hover text</label>
                    <input
                      id="btn_onhover_text_color"
                      type="color"
                      value={form.data.btn_onhover_text_color}
                      onChange={e => form.setData('btn_onhover_text_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                </FormLayout.Group>
              </FormLayout>

              <Divider />

              <div style={{marginTop: '1rem'}}>
                <Text as='h3' variant="headingMd">Buy Now Styles</Text>
              </div>
              <FormLayout>
                <FormLayout.Group>
                  <div>
                    <label htmlFor="buy_bg_color">Background</label>
                    <input
                      id="buy_bg_color"
                      type="color"
                      value={form.data.buy_bg_color}
                      onChange={e => form.setData('buy_bg_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div>
                    <label htmlFor="buy_text_color">Text</label>
                    <input
                      id="buy_text_color"
                      type="color"
                      value={form.data.buy_text_color}
                      onChange={e => form.setData('buy_text_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div>
                    <label htmlFor="buy_onhover_bg_color">Hover background</label>
                    <input
                      id="buy_onhover_bg_color"
                      type="color"
                      value={form.data.buy_onhover_bg_color}
                      onChange={e => form.setData('buy_onhover_bg_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                  <div>
                    <label htmlFor="buy_onhover_text_color">Hover text</label>
                    <input
                      id="buy_onhover_text_color"
                      type="color"
                      value={form.data.buy_onhover_text_color}
                      onChange={e => form.setData('buy_onhover_text_color', e.currentTarget.value)}
                      style={{width:'100%',height:'2.5rem',border:'none'}}
                    />
                  </div>
                </FormLayout.Group>
              </FormLayout>
            </div>
          </Card>
        )}

        <div style={{marginTop: '1rem'}}>
          <Button
            variant="primary"
            submit
            loading={form.processing}
            disabled={form.processing}
          >
            Save Settings
          </Button>
        </div>
      </form>

      {/* Spacer */}
      <div style={{marginBottom: '80px', minHeight: '100px'}} />

      {/* Live Preview */}
      <div style={previewStyles}>
        <div>
          <span style={{fontWeight: 600}}>Sample Product</span>
          <div>
            <span style={{textDecoration: 'line-through', fontWeight: 600}}>
              $120.00
            </span>
            <span
              style={{
                marginLeft: '0.5rem',
                fontWeight: 600,
                color: form.data.cart_price_text_color,
              }}
            >
              $99.00
            </span>
          </div>
        </div>

        <div style={{display: 'flex', gap: '0.5rem'}}>
          <button
            style={isAddHover ? {...addBase, ...addHover} : addBase}
            onMouseEnter={() => setIsAddHover(true)}
            onMouseLeave={() => setIsAddHover(false)}
          >
            ADD TO CART
          </button>

          <button
            style={isBuyHover ? {...buyBase, ...buyHover} : buyBase}
            onMouseEnter={() => setIsBuyHover(true)}
            onMouseLeave={() => setIsBuyHover(false)}
          >
            BUY NOW
          </button>
        </div>
      </div>

      {/* Success Modal */}
      <Modal
        open={modalOpen}
        onClose={() => setModalOpen(false)}
        title="Settings Saved"
        primaryAction={{
          content: 'OK',
          onAction: () => setModalOpen(false),
        }}
      >
        <div style={{padding: '1rem'}}>
          <Text as='h3'>Your settings have been successfully saved.</Text>
        </div>
      </Modal>
    </Page>
  );
}
