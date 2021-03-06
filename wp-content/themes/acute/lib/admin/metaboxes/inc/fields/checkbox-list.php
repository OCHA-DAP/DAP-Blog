<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Checkbox_List_Field' ) )
{
	class RWMB_Checkbox_List_Field
	{
		/**
		 * Get field HTML
		 *
		 * @param string $html
		 * @param mixed  $meta
		 * @param array  $field
		 *
		 * @return string
		 */
		static function html( $html, $meta, $field )
		{
			$meta = (array) $meta;
			$html = array();
			$tpl = '<label><input type="checkbox" class="rwmb-checkbox-list" name="%s" value="%s" %s /> %s</label>';

			foreach ( $field['options'] as $value => $label )
			{
				$html[] = sprintf(
					$tpl,
					$field['field_name'],
					$value,
					checked( in_array( $value, $meta ), 1, false ),
					$label
				);
			}
			return implode( '<br />', $html );
		}

		/**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 *
		 * @return array
		 */
		static function normalize_field( $field )
		{
			$field['multiple']   = true;
			$field['field_name'] = "{$field['id']}[]";
			return $field;
		}
	}
}
