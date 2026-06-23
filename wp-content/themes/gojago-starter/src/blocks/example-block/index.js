import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, ColorPalette } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './style.css';
import './editor.css';
import metadata from './block.json';

const palette = [
  { name: 'Accent', color: '#0f766e' },
  { name: 'Ink', color: '#111827' },
  { name: 'Muted', color: '#5b6472' }
];

registerBlockType(metadata.name, {
  edit: ({ attributes, setAttributes }) => {
    const { eyebrow, heading, body, accent } = attributes;
    const blockProps = useBlockProps({
      className: 'gojago-example-card',
      style: { '--gojago-card-accent': accent }
    });

    return (
      <>
        <InspectorControls>
          <PanelBody title={__('Card style', 'gojago-starter')}>
            <ColorPalette
              colors={palette}
              value={accent}
              onChange={(value) => setAttributes({ accent: value || '#0f766e' })}
            />
          </PanelBody>
        </InspectorControls>
        <article {...blockProps}>
          <RichText
            tagName="p"
            className="gojago-example-card__eyebrow"
            value={eyebrow}
            allowedFormats={[]}
            onChange={(value) => setAttributes({ eyebrow: value })}
            placeholder={__('Eyebrow', 'gojago-starter')}
          />
          <RichText
            tagName="h3"
            className="gojago-example-card__heading"
            value={heading}
            allowedFormats={['core/bold', 'core/italic']}
            onChange={(value) => setAttributes({ heading: value })}
            placeholder={__('Heading', 'gojago-starter')}
          />
          <RichText
            tagName="p"
            className="gojago-example-card__body"
            value={body}
            allowedFormats={['core/bold', 'core/italic', 'core/link']}
            onChange={(value) => setAttributes({ body: value })}
            placeholder={__('Body copy', 'gojago-starter')}
          />
        </article>
      </>
    );
  },
  save: ({ attributes }) => {
    const { eyebrow, heading, body, accent } = attributes;
    const blockProps = useBlockProps.save({
      className: 'gojago-example-card',
      style: { '--gojago-card-accent': accent }
    });

    return (
      <article {...blockProps}>
        <RichText.Content tagName="p" className="gojago-example-card__eyebrow" value={eyebrow} />
        <RichText.Content tagName="h3" className="gojago-example-card__heading" value={heading} />
        <RichText.Content tagName="p" className="gojago-example-card__body" value={body} />
      </article>
    );
  }
});
