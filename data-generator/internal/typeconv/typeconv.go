package typeconv

func Ref[T any](v T) *T {
	return &v
}
