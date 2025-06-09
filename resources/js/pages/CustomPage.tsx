import { Card, Page, Tabs, Text } from '@shopify/polaris';
import { useState, useCallback } from 'react';

export default function CustomPage() {
    const [selected, setSelected] = useState(0);

    const handleTabChange = useCallback(
        (selectedTabIndex: number) => setSelected(selectedTabIndex),
        [],
    );

    const tabs = [
        {
            id: 'all-customers-1',
            content: 'All',
            accessibilityLabel: 'All customers',
            panelID: 'all-customers-content-1',
        },
        {
            id: 'accepts-marketing-1',
            content: 'Accepts marketing',
            panelID: 'accepts-marketing-content-1',
        },
        {
            id: 'repeat-customers-1',
            content: 'Repeat customers',
            panelID: 'repeat-customers-content-1',
        },
        {
            id: 'prospects-1',
            content: 'Prospects',
            panelID: 'prospects-content-1',
        },
    ];

    return (
        <Page>
            <Tabs tabs={tabs} selected={selected} onSelect={handleTabChange}>
                <Card>
                    <Text as="h2" variant="bodyMd">
                        <p>Tab {selected} selected</p>
                    </Text>
                </Card>
            </Tabs>
        </Page>
    );
}
