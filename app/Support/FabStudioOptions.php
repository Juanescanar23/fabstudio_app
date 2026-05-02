<?php

namespace App\Support;

final class FabStudioOptions
{
    public const CLIENT_TYPES = [
        'individual' => 'Persona natural',
        'company' => 'Empresa',
    ];

    public const CLIENT_STATUSES = [
        'active' => 'Activo',
        'inactive' => 'Inactivo',
        'prospect' => 'Prospecto',
    ];

    public const LEAD_STATUSES = [
        'new' => 'Nuevo',
        'contacted' => 'Contactado',
        'qualified' => 'Calificado',
        'converted' => 'Convertido',
        'lost' => 'Perdido',
    ];

    public const PROJECT_STATUSES = [
        'planning' => 'Planeación',
        'active' => 'Activo',
        'on_hold' => 'En pausa',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
    ];

    public const PHASE_STATUSES = [
        'pending' => 'Pendiente',
        'in_progress' => 'En progreso',
        'blocked' => 'Bloqueado',
        'completed' => 'Completado',
    ];

    public const DOCUMENT_CATEGORIES = [
        'general' => 'General',
        'contract' => 'Contrato',
        'design' => 'Diseño',
        'technical' => 'Técnico',
        'quote' => 'Cotización',
        'invoice' => 'Factura',
    ];

    public const VISIBILITIES = [
        'internal' => 'Interno',
        'client' => 'Cliente',
    ];

    public const PUBLISH_STATUSES = [
        'draft' => 'Borrador',
        'review' => 'En revisión',
        'published' => 'Publicado',
        'archived' => 'Archivado',
    ];

    public const VISUAL_ASSET_TYPES = [
        'image' => 'Imagen',
        'render' => 'Render',
        'video' => 'Video',
        'model' => 'Modelo 3D',
        'tour' => 'Tour',
    ];

    public const QUOTE_STATUSES = [
        'draft' => 'Borrador',
        'review' => 'En revisión',
        'sent' => 'Enviada',
        'approved' => 'Aprobada',
        'rejected' => 'Rechazada',
        'expired' => 'Vencida',
    ];

    public const CURRENCIES = [
        'COP' => 'COP',
        'USD' => 'USD',
    ];

    public const COMMENT_TYPES = [
        'comment' => 'Comentario',
        'approval' => 'Aprobación',
        'request_change' => 'Solicitud de cambio',
        'status_update' => 'Actualización de estado',
    ];

    public const DECISIONS = [
        'approved' => 'Aprobado',
        'rejected' => 'Rechazado',
        'changes_requested' => 'Cambios solicitados',
    ];

    public static function statusColor(?string $state): string
    {
        return match ($state) {
            'active', 'approved', 'completed', 'converted', 'published' => 'success',
            'planning', 'new', 'draft', 'pending', 'review' => 'gray',
            'contacted', 'qualified', 'in_progress', 'sent' => 'info',
            'on_hold', 'blocked', 'prospect', 'expired', 'changes_requested' => 'warning',
            'inactive', 'cancelled', 'lost', 'rejected', 'archived' => 'danger',
            default => 'gray',
        };
    }
}
