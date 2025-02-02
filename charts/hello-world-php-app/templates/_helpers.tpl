{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "hello-world-php-app.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" }}
{{- end }}


{{/*
Create a default fully qualified web app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
*/}}
{{- define "webapp.fullname" -}}
{{- printf "%s-%s" .Release.Name "webapp" | trunc 63 | trimSuffix "-" }}
{{- end }}


{{/*
Webapp selector labels
*/}}
{{- define "webapp.selectorLabels" -}}
app.kubernetes.io/name: webapp
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end }}


{{/*
Webapp common labels
*/}}
{{- define "webapp.labels" -}}
helm.sh/chart: {{ include "hello-world-php-app.chart" . }}
{{ include "webapp.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}


{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
*/}}
{{- define "redis.fullname" -}}
{{- printf "%s-%s" .Release.Name "redis" | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Redis selector labels
*/}}
{{- define "redis.selectorLabels" -}}
app.kubernetes.io/name: redis
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end }}


{{/*
Redis common labels
*/}}
{{- define "redis.labels" -}}
helm.sh/chart: {{ include "hello-world-php-app.chart" . }}
{{ include "redis.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}
