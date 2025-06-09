import React from 'react';
import { useForm } from '@inertiajs/react';
import {
    Page,
    Card,
    FormLayout,
    Button,
    Modal,
    TextContainer,
} from '@shopify/polaris';

export default function Customize() {
    const [isHover, setIsHover] = React.useState(false);
    const [modalOpen, setModalOpen] = React.useState(false);
    const [serverResponse, setServerResponse] = React.useState<any>(null);

    const form = useForm({
        cart_bg_color: '#ffffff',
        cart_text_color: '#000000',
        cart_price_text_color: '#333333',
        btn_bg_color: '#008060',
        btn_text_color: '#ffffff',
        btn_onhover_bg_color: '#00664d',
        btn_onhover_text_color: '#ffffff',
    });

    const handleSubmit = React.useCallback((e: React.FormEvent) => {
        e.preventDefault();
        form.post(route('customize'), {
            preserveScroll: true,
            onSuccess: (response) => {
                console.log('Server response:', response);
                setServerResponse(response);
                setModalOpen(true);
            },
        });
    }, [form]);

    // Inline style object for preview bar
    const previewStyles: React.CSSProperties = {
        position: 'fixed',
        bottom: 0,
        left: 0,
        right: 0,
        backgroundColor: form.data.cart_bg_color,
        color: form.data.cart_text_color,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        padding: '1rem',
        boxShadow: '0 1px 6px rgba(0,0,0,0.1)',
        fontSize: '16px',
        zIndex: 1000,
    };

    // Base and hover styles for the preview button
    const baseButtonStyles: React.CSSProperties = {
        backgroundColor: form.data.btn_bg_color,
        color: form.data.btn_text_color,
        padding: '0.8rem 1.5rem',
        border: 'none',
        fontWeight: 600,
        cursor: 'pointer',
        transition: 'background-color 0.3s ease, color 0.3s ease',
    };
    const hoverButtonStyles: React.CSSProperties = {
        backgroundColor: form.data.btn_onhover_bg_color,
        color: form.data.btn_onhover_text_color,
    };
    const buttonStyles = isHover
        ? { ...baseButtonStyles, ...hoverButtonStyles }
        : baseButtonStyles;

    return (
        <Page title="Customize Settings">
            <Card>
                <form onSubmit={handleSubmit}>
                    <FormLayout>
                        <FormLayout.Group>
                            {/* Color pickers */}
                            <div>
                                <label htmlFor="cart_bg_color">Cart background</label>
                                <input
                                    id="cart_bg_color" name="cart_bg_color" type="color"
                                    value={form.data.cart_bg_color}
                                    onChange={e => form.setData('cart_bg_color', e.currentTarget.value)}
                                    style={{ width: '100%', height: '2.5rem', border: 'none' }}
                                />
                            </div>
                            <div>
                                <label htmlFor="cart_text_color">Cart text</label>
                                <input
                                    id="cart_text_color" name="cart_text_color" type="color"
                                    value={form.data.cart_text_color}
                                    onChange={e => form.setData('cart_text_color', e.currentTarget.value)}
                                    style={{ width: '100%', height: '2.5rem', border: 'none' }}
                                />
                            </div>
                            <div>
                                <label htmlFor="cart_price_text_color">Price text</label>
                                <input
                                    id="cart_price_text_color" name="cart_price_text_color" type="color"
                                    value={form.data.cart_price_text_color}
                                    onChange={e => form.setData('cart_price_text_color', e.currentTarget.value)}
                                    style={{ width: '100%', height: '2.5rem', border: 'none' }}
                                />
                            </div>
                        </FormLayout.Group>

                        <FormLayout.Group>
                            <div>
                                <label htmlFor="btn_bg_color">Button background</label>
                                <input
                                    id="btn_bg_color" name="btn_bg_color" type="color"
                                    value={form.data.btn_bg_color}
                                    onChange={e => form.setData('btn_bg_color', e.currentTarget.value)}
                                    style={{ width: '100%', height: '2.5rem', border: 'none' }}
                                />
                            </div>
                            <div>
                                <label htmlFor="btn_text_color">Button text</label>
                                <input
                                    id="btn_text_color" name="btn_text_color" type="color"
                                    value={form.data.btn_text_color}
                                    onChange={e => form.setData('btn_text_color', e.currentTarget.value)}
                                    style={{ width: '100%', height: '2.5rem', border: 'none' }}
                                />
                            </div>
                            <div>
                                <label htmlFor="btn_onhover_bg_color">Hover background</label>
                                <input
                                    id="btn_onhover_bg_color" name="btn_onhover_bg_color" type="color"
                                    value={form.data.btn_onhover_bg_color}
                                    onChange={e => form.setData('btn_onhover_bg_color', e.currentTarget.value)}
                                    style={{ width: '100%', height: '2.5rem', border: 'none' }}
                                />
                            </div>
                            <div>
                                <label htmlFor="btn_onhover_text_color">Hover text</label>
                                <input
                                    id="btn_onhover_text_color" name="btn_onhover_text_color" type="color"
                                    value={form.data.btn_onhover_text_color}
                                    onChange={e => form.setData('btn_onhover_text_color', e.currentTarget.value)}
                                    style={{ width: '100%', height: '2.5rem', border: 'none' }}
                                />
                            </div>
                        </FormLayout.Group>

                        <Button submit variant="primary" loading={form.processing} disabled={form.processing}>
                            Save Settings
                        </Button>
                    </FormLayout>
                </form>
            </Card>

            {/* Live Preview */}
            <>
                <div style={{ marginBottom: '80px', position: 'relative', minHeight: '120px' }}>
                    <div style={previewStyles}>
                        <div>
                            <strong>Sample Product</strong>
                            <div>
                                <span style={{ textDecoration: 'line-through' }}>$120.00</span>
                                <span style={{ marginRight: '0.5rem', color: form.data.cart_price_text_color }}> $99.00</span>
                            </div>
                        </div>
                        <button
                            style={buttonStyles}
                            onMouseEnter={() => setIsHover(true)}
                            onMouseLeave={() => setIsHover(false)}
                        >
                            ADD TO CART
                        </button>
                    </div>
                </div>
            </>

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
                <Modal.Section>
                    <TextContainer>
                        <p>Your settings have been successfully saved.</p>
                    </TextContainer>
                </Modal.Section>
            </Modal>
        </Page>
    );
}
