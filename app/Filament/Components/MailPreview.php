<?php

namespace App\Filament\Components;

use App\Actions\RenderMailContent;
use App\Mail\GenericMail;
use App\Models\Exhibitor;
use Filament\Infolists\Components\Entry;
use Filament\Support\Components\Contracts\HasEmbeddedView;
use Illuminate\Support\Js;

class MailPreview extends Entry implements HasEmbeddedView
{
    public function toEmbeddedHtml(): string
    {
        $state = $this->getState();

        $state = (new RenderMailContent)($state, auth()->user()->exhibitor ?? Exhibitor::first());

        if ($state === '') {
            return '';
        }

        $content = (new GenericMail('', $state))->render();
        $escapedContent = Js::from($content);

        return $this->wrapEmbeddedHtml(<<<HTML
                <script>
                    class EmbeddedWebview extends HTMLElement {
                        static get observedAttributes() {
                            return ['data-content'];
                        }

                        constructor() {
                            super();
                            this.shadow = this.attachShadow({ mode: 'closed' });
                        }

                        connectedCallback() {
                            const content = this.getAttribute('data-content');
                            this.update(content)
                        }

                        update(content) {
                            if (! content) {
                                return;
                            }

                            this.shadow.innerHTML = eval(content)
                            this.shadow.innerHTML += `<style>
                                :host {
                                    all: initial;
                                    display: block;
                                }
                            </style>`;
                        }

                        attributeChangedCallback (name, oldValue, newValue) {
                            this.update(newValue);
                        }

                    }

                    if (!window.customElements.get('embedded-webview')) {
                        window.customElements.define('embedded-webview', EmbeddedWebview);
                    }
                </script>

                <embedded-webview data-content="{$escapedContent}" />
            HTML);
    }
}
